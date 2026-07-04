/**
 * Nihil Novi - Presocratics Widgets Controller
 */
(function() {
    // === 1. WIDGET: MUTABILIDAD DEL AGUA ===
    var particleBox = null;
    var maxParticles = 40;
    var particles = [];

    function initParticles() {
        particleBox = document.getElementById('tales-particle-box');
        if (!particleBox) return;

        particleBox.innerHTML = '';
        particles = [];
        for (var i = 0; i < maxParticles; i++) {
            var p = document.createElement('div');
            p.className = 'tales-particle';
            p.style.width = '6px';
            p.style.height = '6px';
            p.style.left = Math.random() * 95 + '%';
            p.style.top = Math.random() * 85 + '%';
            particleBox.appendChild(p);
            particles.push(p);
        }
        updateTalesTemp(20);
    }

    function updateTalesTemp(temp) {
        temp = parseInt(temp);
        var badge = document.getElementById('tales-temp-badge');
        var info = document.getElementById('tales-state-info');
        if (badge) badge.innerText = temp + '°C';
        if (!info || particles.length === 0) return;

        if (temp <= 0) {
            info.innerHTML = "<strong>Estado Sólido (Hielo / Tierra compactada)</strong><br>El frío condensa el agua transformándola en hielo sólido. Tales observaba en este proceso de petrificación cómo el archē húmedo adquiere la rigidez de los sedimentos terrestres.";
            particles.forEach(function(p) {
                p.style.backgroundColor = 'rgba(255,255,255,0.7)';
                p.style.transform = 'scale(1.4)';
                p.style.boxShadow = '0 0 4px #fff';
            });
        } else if (temp > 0 && temp < 100) {
            info.innerHTML = "<strong>Estado Líquido (Agua primordial / Hydōr / ὕδωρ)</strong><br>A temperatura ambiente, el agua fluye libremente. Es la base de la vida, nutre las semillas y compone todos los organismos. Su humedad sustenta la hipótesis de Tales en la Metafísica.";
            particles.forEach(function(p) {
                p.style.backgroundColor = 'rgba(99,179,237,0.6)';
                p.style.transform = 'scale(1) translate(' + (Math.random() * 10 - 5) + 'px, ' + (Math.random() * 10 - 5) + 'px)';
                p.style.boxShadow = 'none';
            });
        } else {
            info.innerHTML = "<strong>Estado Gaseoso (Vapor / Aire céntrico y Fuego)</strong><br>El calor extremo dilata el agua transformándola en vapor ascendente. El agua nutre las exhalaciones calientes que forman el Sol y los astros celestes, cerrando el ciclo del cosmos.";
            particles.forEach(function(p) {
                p.style.backgroundColor = 'rgba(237,137,54,0.7)';
                p.style.transform = 'scale(0.8) translate(' + (Math.random() * 40 - 20) + 'px, -40px)';
                p.style.boxShadow = '0 0 6px #ed8936';
            });
        }
    }

    // === 2. WIDGET: LA BALANZA HILOZOÍSTA ===
    var audioCtx = null;
    var slider = null;

    function initAudio() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
    }

    function doUpdateMagnet(val) {
        val = parseInt(val);
        var iron = document.getElementById('tales-balance-iron');
        var badge = document.getElementById('tales-magnet-badge');
        var line1 = document.getElementById('line-1');
        var line2 = document.getElementById('line-2');
        var line3 = document.getElementById('line-3');
        
        if (!iron || !badge) return;

        var maxTravel = 230; 
        var travelPx = (val / 100) * maxTravel;
        var rightPos = 40 + travelPx;
        iron.style.right = rightPos + 'px';
        
        var distMeters = 10 - (val / 10);
        badge.innerText = distMeters.toFixed(1) + ' m';
        
        var angle = 30 - (val * 0.3); 
        var opacity = 0.2 + (val * 0.008); 
        
        if(line1) {
            line1.style.transform = 'rotate(' + angle + 'deg)';
            line1.style.opacity = opacity;
            line1.style.backgroundColor = val > 70 ? '#c5a059' : 'rgba(197,160,89,0.3)';
        }
        if(line2) {
            line2.style.transform = 'rotate(0deg)';
            line2.style.opacity = opacity;
            line2.style.backgroundColor = val > 70 ? '#c5a059' : 'rgba(197,160,89,0.3)';
        }
        if(line3) {
            line3.style.transform = 'rotate(-' + angle + 'deg)';
            line3.style.opacity = opacity;
            line3.style.backgroundColor = val > 70 ? '#c5a059' : 'rgba(197,160,89,0.3)';
        }

        if (val > 10 && val < 90) {
            initAudio();
            if (audioCtx && audioCtx.state === 'running') {
                var osc = audioCtx.createOscillator();
                var gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(100 + (val * 2.5), audioCtx.currentTime);
                gain.gain.setValueAtTime(0.02 * (val / 100), audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.1);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.1);
            }
        }

        if (val >= 90) {
            iron.style.right = (40 + maxTravel) + 'px';
            badge.innerText = '0.0 m (Unido)';
            
            initAudio();
            if (audioCtx && audioCtx.state === 'running') {
                var osc = audioCtx.createOscillator();
                var gain = audioCtx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(180, audioCtx.currentTime);
                gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.3);
            }
        }
    }

    function initSliderWidget() {
        slider = document.getElementById('tales-magnet-slide');
        var tempSlide = document.getElementById('tales-temp-slide');
        
        // 1. Setup Slider Magneto
        if (slider) {
            slider.addEventListener('input', function() {
                doUpdateMagnet(this.value);
            });
            doUpdateMagnet(0);
        }

        // 2. Setup Slider Temperatura Agua
        if (tempSlide) {
            tempSlide.addEventListener('input', function() {
                updateTalesTemp(this.value);
            });
            initParticles();
        }
    }

    function startWidgetScanner() {
        var sliderEl = document.getElementById('tales-magnet-slide');
        var tempSlideEl = document.getElementById('tales-temp-slide');
        if (sliderEl || tempSlideEl) {
            initSliderWidget();
        } else {
            setTimeout(startWidgetScanner, 200);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startWidgetScanner);
    } else {
        startWidgetScanner();
    }
})();
