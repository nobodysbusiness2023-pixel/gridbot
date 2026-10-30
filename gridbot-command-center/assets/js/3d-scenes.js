/**
 * GridBot Command Center - 3D Scenes and Rendering
 * 
 * @package GridBot_Command_Center
 */

class GridBot3D {
    constructor(containerId, config = {}) {
        this.containerId = containerId;
        this.container = document.getElementById(containerId);
        this.config = config;
        this.scene = null;
        this.camera = null;
        this.renderer = null;
        this.controls = null;
        this.animations = [];
        this.isPlaying = true;
        this.clock = new THREE.Clock();
        this.stats = null;
        
        this.init();
    }
    
    init() {
        if (!this.container) {
            console.warn(`Container ${this.containerId} not found`);
            return;
        }
        
        this.wrapper = this.container.closest('.gridbot-3d-visualizer, .three-scene-container');
        
        this.createScene();
        this.createCamera();
        this.createRenderer();
        this.createLighting();
        this.createControls();
        this.createObjects();
        this.setupEventListeners();
        this.setupWrapperControls();
        this.startStats();
        this.animate();
        
        // Mark as initialized and clear any loading overlay
        this.container.dataset.initialized = 'true';
        if (this.wrapper) {
            this.wrapper.querySelectorAll('.visualizer-loading, .three-loading').forEach(el => { el.style.display = 'none'; });
        }
    }
    
    createScene() {
        this.scene = new THREE.Scene();
        
        // Set background color
        const bgColor = this.config.scene?.background || '#0a0a0a';
        this.scene.background = new THREE.Color(bgColor);
        
        // Add fog for depth
        if (this.config.scene?.fog?.enabled) {
            const fogColor = this.config.scene.fog.color || bgColor;
            const fogNear = this.config.scene.fog.near || 1;
            const fogFar = this.config.scene.fog.far || 1000;
            this.scene.fog = new THREE.Fog(fogColor, fogNear, fogFar);
        }
    }
    
    createCamera() {
        const cameraConfig = this.config.camera || {};
        const fov = cameraConfig.fov || 75;
        const aspect = this.getSize().width / this.getSize().height;
        const near = cameraConfig.near || 0.1;
        const far = cameraConfig.far || 1000;
        
        this.camera = new THREE.PerspectiveCamera(fov, aspect, near, far);
        
        // Set camera position
        const position = cameraConfig.position || [0, 0, 50];
        this.camera.position.set(position[0], position[1], position[2]);
    }
    
    createRenderer() {
        const rendererConfig = this.config.renderer || {};
        
        this.renderer = new THREE.WebGLRenderer({
            antialias: rendererConfig.antialias !== false,
            alpha: rendererConfig.alpha === true,
            powerPreference: rendererConfig.powerPreference || 'high-performance'
        });
        
        const size = this.getSize();
        this.renderer.setSize(size.width, size.height);
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        this.renderer.shadowMap.enabled = true;
        this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
        this.renderer.toneMappingExposure = 1.0;
        
        this.container.appendChild(this.renderer.domElement);
    }
    
    getSize() {
        return {
            width: this.container.clientWidth || window.innerWidth,
            height: this.container.clientHeight || 400
        };
    }
    
    setupWrapperControls() {
        if (!this.wrapper) return;
        const bind = (selector, handler) => {
            this.wrapper.querySelectorAll(selector).forEach(btn => btn.addEventListener('click', handler));
        };
        bind('.visualizer-play', () => this.play());
        bind('.visualizer-pause', () => this.pause());
        bind('.visualizer-reset', () => this.reset());
        if (this.wrapper.dataset.autoPlay === 'false') {
            this.isPlaying = false;
        }
    }
    
    /**
     * Simulated grid activity for the stats readout next to the scene.
     * Numbers are illustrative only; they are not market data.
     */
    startStats() {
        if (!this.wrapper) return;
        const gridsEl = this.wrapper.querySelector('.stat-active-grids');
        const profitEl = this.wrapper.querySelector('.stat-total-profit');
        const rateEl = this.wrapper.querySelector('.stat-success-rate');
        if (!gridsEl && !profitEl && !rateEl) return;
        
        const levels = (this.config.grid && this.config.grid.levels) || 10;
        this.simStats = { activeGrids: levels, profit: 0, trades: 0, wins: 0 };
        const render = () => {
            if (gridsEl) gridsEl.textContent = String(this.simStats.activeGrids);
            if (profitEl) profitEl.textContent = '$' + this.simStats.profit.toFixed(2);
            if (rateEl) rateEl.textContent = (this.simStats.trades ? Math.round(this.simStats.wins / this.simStats.trades * 100) : 0) + '%';
        };
        render();
        this.statsTimer = setInterval(() => {
            if (!this.isPlaying) return;
            const win = Math.random() < 0.88;
            this.simStats.trades += 1;
            if (win) { this.simStats.wins += 1; this.simStats.profit += 2 + Math.random() * 12; }
            else { this.simStats.profit -= Math.random() * 6; }
            render();
        }, 1500);
    }
    
    createLighting() {
        const lighting = this.config.lighting || {};
        
        // Ambient light
        if (lighting.ambient) {
            const ambientColor = lighting.ambient.color || '#404040';
            const ambientIntensity = lighting.ambient.intensity || 0.4;
            const ambientLight = new THREE.AmbientLight(ambientColor, ambientIntensity);
            this.scene.add(ambientLight);
        }
        
        // Directional light
        if (lighting.directional) {
            const dirColor = lighting.directional.color || '#2dd4bf';
            const dirIntensity = lighting.directional.intensity || 0.8;
            const dirPosition = lighting.directional.position || [10, 10, 5];
            
            const directionalLight = new THREE.DirectionalLight(dirColor, dirIntensity);
            directionalLight.position.set(dirPosition[0], dirPosition[1], dirPosition[2]);
            directionalLight.castShadow = true;
            directionalLight.shadow.mapSize.width = 2048;
            directionalLight.shadow.mapSize.height = 2048;
            this.scene.add(directionalLight);
        }
        
        // Point light
        if (lighting.point) {
            const pointColor = lighting.point.color || '#f59e0b';
            const pointIntensity = lighting.point.intensity || 0.6;
            const pointPosition = lighting.point.position || [-10, -10, 10];
            const pointDistance = lighting.point.distance || 50;
            
            const pointLight = new THREE.PointLight(pointColor, pointIntensity, pointDistance);
            pointLight.position.set(pointPosition[0], pointPosition[1], pointPosition[2]);
            this.scene.add(pointLight);
        }
    }
    
    createControls() {
        if (typeof THREE.OrbitControls !== 'undefined') {
            this.controls = new THREE.OrbitControls(this.camera, this.renderer.domElement);
            this.controls.enableDamping = true;
            this.controls.dampingFactor = 0.05;
            this.controls.screenSpacePanning = false;
            this.controls.minDistance = 10;
            this.controls.maxDistance = 200;
            this.controls.maxPolarAngle = Math.PI / 2;
        }
    }
    
    createObjects() {
        // Create grid visualization
        this.createGridVisualization();
        
        // Create particle system
        this.createParticleSystem();
        
        // Create trading visualization
        this.createTradingVisualization();
        
        // Create floating elements
        this.createFloatingElements();
    }
    
    createGridVisualization() {
        const gridConfig = this.config.grid || {};
        const size = gridConfig.size || 20;
        const spacing = gridConfig.spacing || 2;
        const levels = gridConfig.levels || 10;
        
        const gridGroup = new THREE.Group();
        
        // Create grid lines
        const gridMaterial = this.createMaterial('grid');
        
        for (let i = 0; i <= levels; i++) {
            for (let j = 0; j <= levels; j++) {
                // Vertical lines
                const verticalGeometry = new THREE.BoxGeometry(0.1, size, 0.1);
                const verticalMesh = new THREE.Mesh(verticalGeometry, gridMaterial);
                verticalMesh.position.set(
                    (i - levels / 2) * spacing,
                    0,
                    (j - levels / 2) * spacing
                );
                gridGroup.add(verticalMesh);
                
                // Horizontal lines
                const horizontalGeometry = new THREE.BoxGeometry(size, 0.1, 0.1);
                const horizontalMesh = new THREE.Mesh(horizontalGeometry, gridMaterial);
                horizontalMesh.position.set(
                    0,
                    (i - levels / 2) * spacing,
                    (j - levels / 2) * spacing
                );
                gridGroup.add(horizontalMesh);
            }
        }
        
        this.scene.add(gridGroup);
        this.gridGroup = gridGroup;
    }
    
    createParticleSystem() {
        const particleConfig = this.config.particles || {};
        const count = particleConfig.count || 1000;
        
        const particles = new THREE.BufferGeometry();
        const positions = new Float32Array(count * 3);
        const colors = new Float32Array(count * 3);
        const sizes = new Float32Array(count);
        
        const color = new THREE.Color();
        
        for (let i = 0; i < count; i++) {
            const i3 = i * 3;
            
            // Position
            positions[i3] = (Math.random() - 0.5) * 100;
            positions[i3 + 1] = (Math.random() - 0.5) * 100;
            positions[i3 + 2] = (Math.random() - 0.5) * 100;
            
            // Color
            color.setHSL(Math.random() * 0.3 + 0.5, 0.75, 0.5);
            colors[i3] = color.r;
            colors[i3 + 1] = color.g;
            colors[i3 + 2] = color.b;
            
            // Size
            sizes[i] = Math.random() * 2 + 0.5;
        }
        
        particles.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        particles.setAttribute('color', new THREE.BufferAttribute(colors, 3));
        particles.setAttribute('size', new THREE.BufferAttribute(sizes, 1));
        
        const particleMaterial = new THREE.PointsMaterial({
            size: 0.5,
            vertexColors: true,
            blending: THREE.AdditiveBlending,
            transparent: true,
            opacity: 0.6
        });
        
        this.particleSystem = new THREE.Points(particles, particleMaterial);
        this.scene.add(this.particleSystem);
        
        // Store original positions for animation
        this.particlePositions = positions.slice();
    }
    
    createTradingVisualization() {
        const tradingConfig = this.config.trading || {};
        const priceRange = tradingConfig.price?.range || [80, 120];
        
        const tradingGroup = new THREE.Group();
        
        // Create price line
        const pricePoints = [];
        for (let i = 0; i < 100; i++) {
            const price = priceRange[0] + (priceRange[1] - priceRange[0]) * Math.random();
            pricePoints.push(new THREE.Vector3(i * 0.2 - 10, price - 100, 0));
        }
        
        const priceCurve = new THREE.CatmullRomCurve3(pricePoints);
        const priceGeometry = new THREE.TubeGeometry(priceCurve, 100, 0.2, 8, false);
        const priceMaterial = new THREE.MeshBasicMaterial({
            color: '#2dd4bf',
            transparent: true,
            opacity: 0.9
        });
        
        const priceLine = new THREE.Mesh(priceGeometry, priceMaterial);
        tradingGroup.add(priceLine);
        
        // Create buy orders
        const buyOrders = tradingConfig.orders?.buy?.count || 5;
        for (let i = 0; i < buyOrders; i++) {
            const buyGeometry = new THREE.SphereGeometry(0.5, 16, 16);
            const buyMaterial = new THREE.MeshBasicMaterial({ color: '#059669' });
            
            const buyOrder = new THREE.Mesh(buyGeometry, buyMaterial);
            buyOrder.position.set(
                (i - buyOrders / 2) * 2,
                Math.random() * 10 - 15,
                Math.random() * 5 - 2.5
            );
            tradingGroup.add(buyOrder);
        }
        
        // Create sell orders
        const sellOrders = tradingConfig.orders?.sell?.count || 5;
        for (let i = 0; i < sellOrders; i++) {
            const sellGeometry = new THREE.SphereGeometry(0.5, 16, 16);
            const sellMaterial = new THREE.MeshBasicMaterial({ color: '#dc2626' });
            
            const sellOrder = new THREE.Mesh(sellGeometry, sellMaterial);
            sellOrder.position.set(
                (i - sellOrders / 2) * 2,
                Math.random() * 10 + 5,
                Math.random() * 5 - 2.5
            );
            tradingGroup.add(sellOrder);
        }
        
        this.scene.add(tradingGroup);
        this.tradingGroup = tradingGroup;
    }
    
    createFloatingElements() {
        // Create floating UI elements
        const floatingGroup = new THREE.Group();
        
        // Floating cards
        for (let i = 0; i < 5; i++) {
            const cardGeometry = new THREE.PlaneGeometry(3, 2);
            const cardMaterial = new THREE.MeshBasicMaterial({
                color: '#1a1a1a',
                transparent: true,
                opacity: 0.8,
                side: THREE.DoubleSide
            });
            
            const card = new THREE.Mesh(cardGeometry, cardMaterial);
            card.position.set(
                (Math.random() - 0.5) * 50,
                (Math.random() - 0.5) * 30,
                (Math.random() - 0.5) * 20
            );
            card.rotation.set(
                Math.random() * 0.2,
                Math.random() * 0.2,
                Math.random() * 0.1
            );
            
            floatingGroup.add(card);
        }
        
        this.scene.add(floatingGroup);
        this.floatingGroup = floatingGroup;
    }
    
    createMaterial(type) {
        const materials = this.config.materials || {};
        
        switch (type) {
            case 'grid':
                const gridMat = materials.grid || {};
                return new THREE.MeshPhongMaterial({
                    color: gridMat.color || '#2dd4bf',
                    emissive: gridMat.emissive || '#2dd4bf',
                    emissiveIntensity: gridMat.emissiveIntensity || 0.2,
                    transparent: gridMat.transparent !== false,
                    opacity: gridMat.opacity || 0.8
                });
                
            case 'particles':
                const particleMat = materials.particles || {};
                return new THREE.PointsMaterial({
                    color: particleMat.color || '#2dd4bf',
                    size: particleMat.size || 0.5,
                    transparent: particleMat.transparent !== false,
                    opacity: particleMat.opacity || 0.6,
                    blending: THREE.AdditiveBlending
                });
                
            default:
                return new THREE.MeshBasicMaterial({
                    color: '#2dd4bf'
                });
        }
    }
    
    setupEventListeners() {
        // Window resize
        window.addEventListener('resize', () => this.onWindowResize());
        
        // Mouse move for particle interaction
        this.renderer.domElement.addEventListener('mousemove', (e) => this.onMouseMove(e));
        
        // Touch events for mobile
        this.renderer.domElement.addEventListener('touchstart', (e) => this.onTouchStart(e));
        this.renderer.domElement.addEventListener('touchmove', (e) => this.onTouchMove(e));
        
        // Control buttons
        this.setupControlButtons();
    }
    
    setupControlButtons() {
        const playBtn = document.getElementById(`${this.containerId}-play`);
        const pauseBtn = document.getElementById(`${this.containerId}-pause`);
        const resetBtn = document.getElementById(`${this.containerId}-reset`);
        const fullscreenBtn = document.getElementById(`${this.containerId}-fullscreen`);
        
        if (playBtn) {
            playBtn.addEventListener('click', () => this.play());
        }
        
        if (pauseBtn) {
            pauseBtn.addEventListener('click', () => this.pause());
        }
        
        if (resetBtn) {
            resetBtn.addEventListener('click', () => this.reset());
        }
        
        if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', () => this.toggleFullscreen());
        }
    }
    
    onWindowResize() {
        if (!this.camera || !this.renderer) return;
        
        const { width, height } = this.getSize();
        
        this.camera.aspect = width / height;
        this.camera.updateProjectionMatrix();
        
        this.renderer.setSize(width, height);
    }
    
    onMouseMove(event) {
        const rect = this.renderer.domElement.getBoundingClientRect();
        const mouse = {
            x: ((event.clientX - rect.left) / rect.width) * 2 - 1,
            y: -((event.clientY - rect.top) / rect.height) * 2 + 1
        };
        
        // Update particle system based on mouse position
        if (this.particleSystem) {
            const positions = this.particleSystem.geometry.attributes.position.array;
            const originalPositions = this.particlePositions;
            
            for (let i = 0; i < positions.length; i += 3) {
                const dx = mouse.x * 10;
                const dy = mouse.y * 10;
                
                positions[i] = originalPositions[i] + dx;
                positions[i + 1] = originalPositions[i + 1] + dy;
            }
            
            this.particleSystem.geometry.attributes.position.needsUpdate = true;
        }
    }
    
    onTouchStart(event) {
        event.preventDefault();
    }
    
    onTouchMove(event) {
        event.preventDefault();
        
        if (event.touches.length === 1) {
            const touch = event.touches[0];
            this.onMouseMove(touch);
        }
    }
    
    animate() {
        if (!this.isPlaying) return;
        
        requestAnimationFrame(() => this.animate());
        
        const delta = this.clock.getDelta();
        const elapsed = this.clock.getElapsedTime();
        
        this.updateAnimations(delta, elapsed);
        this.updateParticles(delta, elapsed);
        this.updateTradingVisualization(delta, elapsed);
        
        if (this.controls) {
            this.controls.update();
        }
        
        this.render();
    }
    
    updateAnimations(delta, elapsed) {
        // Rotate grid
        if (this.gridGroup) {
            this.gridGroup.rotation.y += delta * 0.1;
        }
        
        // Animate particles
        if (this.particleSystem) {
            this.particleSystem.rotation.y += delta * 0.05;
        }
        
        // Float trading elements
        if (this.tradingGroup) {
            this.tradingGroup.children.forEach((child, index) => {
                if (child.geometry.type === 'SphereGeometry') {
                    child.position.y += Math.sin(elapsed * 2 + index) * 0.01;
                }
            });
        }
    }
    
    updateParticles(delta, elapsed) {
        if (!this.particleSystem) return;
        
        const positions = this.particleSystem.geometry.attributes.position.array;
        const sizes = this.particleSystem.geometry.attributes.size.array;
        
        for (let i = 0; i < positions.length; i += 3) {
            const index = i / 3;
            
            // Floating motion
            positions[i + 1] += Math.sin(elapsed + index * 0.1) * 0.01;
            
            // Pulsing sizes
            sizes[index] = (Math.sin(elapsed * 3 + index * 0.1) + 1) * 0.5 + 0.5;
        }
        
        this.particleSystem.geometry.attributes.position.needsUpdate = true;
        this.particleSystem.geometry.attributes.size.needsUpdate = true;
    }
    
    updateTradingVisualization(delta, elapsed) {
        if (!this.tradingGroup) return;
        
        // Animate price line
        const priceLine = this.tradingGroup.children.find(child => 
            child.geometry && child.geometry.type === 'TubeGeometry'
        );
        
        if (priceLine) {
            priceLine.material.opacity = 0.7 + Math.sin(elapsed * 2) * 0.25;
        }
    }
    
    render() {
        if (this.renderer && this.scene && this.camera) {
            this.renderer.render(this.scene, this.camera);
        }
    }
    
    play() {
        if (this.isPlaying) return;
        this.isPlaying = true;
        this.clock.getDelta();
        this.animate();
    }
    
    pause() {
        this.isPlaying = false;
    }
    
    reset() {
        if (this.simStats) {
            this.simStats.profit = 0; this.simStats.trades = 0; this.simStats.wins = 0;
        }
        if (this.camera && this.config.camera) {
            const position = this.config.camera.position || [0, 0, 50];
            this.camera.position.set(position[0], position[1], position[2]);
            this.camera.lookAt(0, 0, 0);
        }
        
        if (this.controls) {
            this.controls.reset();
        }
    }
    
    toggleFullscreen() {
        if (!document.fullscreenElement) {
            this.container.requestFullscreen().catch(err => {
                console.log(`Error attempting to enable fullscreen: ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    }
    
    dispose() {
        this.pause();
        if (this.statsTimer) clearInterval(this.statsTimer);
        
        if (this.renderer) {
            this.renderer.dispose();
        }
        
        if (this.scene) {
            // Dispose of all geometries and materials
            this.scene.traverse(object => {
                if (object.geometry) {
                    object.geometry.dispose();
                }
                
                if (object.material) {
                    if (Array.isArray(object.material)) {
                        object.material.forEach(material => material.dispose());
                    } else {
                        object.material.dispose();
                    }
                }
            });
        }
    }
}

// Background 3D Scene
class Background3D extends GridBot3D {
    constructor(containerId, config = {}) {
        // Override config for background scene
        const backgroundConfig = {
            ...config,
            camera: {
                position: [0, 0, 100],
                fov: 60,
                ...config.camera
            },
            particles: {
                count: 2000,
                ...config.particles
            }
        };
        
        super(containerId, backgroundConfig);
    }
    
    createObjects() {
        // Create subtle background grid
        this.createBackgroundGrid();
        
        // Create enhanced particle system
        this.createEnhancedParticles();
        
        // Create floating geometric shapes
        this.createFloatingShapes();
    }
    
    createBackgroundGrid() {
        const gridSize = 100;
        const divisions = 20;
        const gridHelper = new THREE.GridHelper(gridSize, divisions, '#2dd4bf', '#1a1a1a');
        gridHelper.rotation.x = Math.PI / 2;
        gridHelper.position.z = -50;
        gridHelper.material.opacity = 0.1;
        gridHelper.material.transparent = true;
        
        this.scene.add(gridHelper);
        this.backgroundGrid = gridHelper;
    }
    
    createEnhancedParticles() {
        const particleConfig = this.config.particles || {};
        const count = particleConfig.count || 2000;
        
        const particles = new THREE.BufferGeometry();
        const positions = new Float32Array(count * 3);
        const colors = new Float32Array(count * 3);
        const sizes = new Float32Array(count);
        
        for (let i = 0; i < count; i++) {
            const i3 = i * 3;
            
            // Create sphere distribution
            const radius = Math.random() * 50 + 20;
            const theta = Math.random() * Math.PI * 2;
            const phi = Math.random() * Math.PI;
            
            positions[i3] = radius * Math.sin(phi) * Math.cos(theta);
            positions[i3 + 1] = radius * Math.sin(phi) * Math.sin(theta);
            positions[i3 + 2] = radius * Math.cos(phi);
            
            // Gradient colors
            const t = Math.random();
            const color = new THREE.Color().setHSL(0.5 + t * 0.2, 0.8, 0.5);
            
            colors[i3] = color.r;
            colors[i3 + 1] = color.g;
            colors[i3 + 2] = color.b;
            
            sizes[i] = Math.random() * 3 + 1;
        }
        
        particles.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        particles.setAttribute('color', new THREE.BufferAttribute(colors, 3));
        particles.setAttribute('size', new THREE.BufferAttribute(sizes, 1));
        
        const particleMaterial = new THREE.PointsMaterial({
            size: 2,
            vertexColors: true,
            blending: THREE.AdditiveBlending,
            transparent: true,
            opacity: 0.4
        });
        
        this.backgroundParticles = new THREE.Points(particles, particleMaterial);
        this.scene.add(this.backgroundParticles);
    }
    
    createFloatingShapes() {
        const shapesGroup = new THREE.Group();
        
        // Create various geometric shapes
        const geometries = [
            new THREE.TetrahedronGeometry(2),
            new THREE.OctahedronGeometry(2),
            new THREE.IcosahedronGeometry(2),
            new THREE.TorusGeometry(2, 0.5, 16, 100),
            new THREE.TorusKnotGeometry(1.5, 0.5, 100, 16)
        ];
        
        for (let i = 0; i < 20; i++) {
            const geometry = geometries[Math.floor(Math.random() * geometries.length)];
            const material = new THREE.MeshPhongMaterial({
                color: new THREE.Color().setHSL(Math.random() * 0.3 + 0.5, 0.8, 0.5),
                transparent: true,
                opacity: 0.3,
                wireframe: Math.random() > 0.5
            });
            
            const mesh = new THREE.Mesh(geometry, material);
            mesh.position.set(
                (Math.random() - 0.5) * 80,
                (Math.random() - 0.5) * 80,
                (Math.random() - 0.5) * 80
            );
            
            mesh.userData = {
                rotationSpeed: {
                    x: (Math.random() - 0.5) * 0.02,
                    y: (Math.random() - 0.5) * 0.02,
                    z: (Math.random() - 0.5) * 0.02
                },
                floatSpeed: Math.random() * 0.01 + 0.005,
                floatOffset: Math.random() * Math.PI * 2
            };
            
            shapesGroup.add(mesh);
        }
        
        this.floatingShapes = shapesGroup;
        this.scene.add(shapesGroup);
    }
    
    updateAnimations(delta, elapsed) {
        super.updateAnimations(delta, elapsed);
        
        // Rotate background grid
        if (this.backgroundGrid) {
            this.backgroundGrid.rotation.z += delta * 0.05;
        }
        
        // Animate background particles
        if (this.backgroundParticles) {
            this.backgroundParticles.rotation.y += delta * 0.02;
            this.backgroundParticles.rotation.x += delta * 0.01;
        }
        
        // Animate floating shapes
        if (this.floatingShapes) {
            this.floatingShapes.children.forEach((shape, index) => {
                const userData = shape.userData;
                
                // Rotation
                shape.rotation.x += userData.rotationSpeed.x;
                shape.rotation.y += userData.rotationSpeed.y;
                shape.rotation.z += userData.rotationSpeed.z;
                
                // Floating motion
                shape.position.y += Math.sin(elapsed * userData.floatSpeed + userData.floatOffset) * 0.02;
                
                // Pulsing opacity
                shape.material.opacity = 0.2 + Math.sin(elapsed * 2 + index) * 0.1;
            });
        }
    }
}

// Initialize 3D scenes when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize background 3D scene
    const backgroundContainer = document.getElementById('three-background');
    if (backgroundContainer && window.gridbotccConfig && window.gridbotccConfig.is3DEnabled) {
        window.background3D = new Background3D('three-background', window.gridbotcc3dConfig?.scene || {});
    }
    
    // Initialize hero 3D visualizer
    const heroVisualizer = document.getElementById('hero-grid-visualizer');
    if (heroVisualizer && window.gridbotccConfig && window.gridbotccConfig.is3DEnabled) {
        window.hero3D = new GridBot3D('hero-grid-visualizer', {
            camera: {
                position: [0, 0, 30],
                fov: 60
            },
            grid: {
                size: 10,
                spacing: 1,
                levels: 8
            },
            particles: {
                count: 500
            }
        });
    }
    
    // Initialize process visualizer
    const processVisualizer = document.getElementById('process-visualizer');
    if (processVisualizer && window.gridbotccConfig && window.gridbotccConfig.is3DEnabled) {
        window.process3D = new GridBot3D('process-visualizer', {
            camera: {
                position: [0, 0, 20],
                fov: 45
            },
            grid: {
                size: 5,
                spacing: 0.5,
                levels: 6
            },
            particles: {
                count: 200
            }
        });
    }
});

// Export for use in other scripts
window.GridBot3D = GridBot3D;
window.Background3D = Background3D;