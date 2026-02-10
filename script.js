// --- MENU MOBILE ---
const menuBtn = document.querySelector(".menu-btn");
const menu = document.querySelector(".menu");

if (menuBtn && menu) {
    menuBtn.addEventListener("click", () => {
        menu.classList.toggle("active");
    });
}

// --- CANVAS DE PARTÍCULAS (Ajustado para o novo ID) ---
const canvas = document.getElementById('canvas-bg') || document.getElementById('canvas-particles-global');
if (canvas) {
    const ctx = canvas.getContext('2d');
    let particlesArray = [];

    function resizeCanvas() {
        canvas.width = canvas.parentElement.offsetWidth;
        canvas.height = canvas.parentElement.offsetHeight;
    }
    resizeCanvas();

    class Particle {
        constructor(x, y, size, color, speedX, speedY) {
            this.x = x;
            this.y = y;
            this.size = size;
            this.color = color;
            this.speedX = speedX;
            this.speedY = speedY;
        }
        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
            if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
        }
        draw() {
            ctx.fillStyle = this.color;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    function initParticles() {
        particlesArray = [];
        for (let i = 0; i < 50; i++) { 
            let size = Math.random() * 2 + 1;
            let x = Math.random() * canvas.width;
            let y = Math.random() * canvas.height;
            let speedX = (Math.random() - 0.5) * 0.5;
            let speedY = (Math.random() - 0.5) * 0.5;
            let color = Math.random() > 0.6 ? 'rgba(255,0,0,0.3)' : 'rgba(255,255,255,0.2)';
            particlesArray.push(new Particle(x, y, size, color, speedX, speedY));
        }
    }

    function animateParticles() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particlesArray.forEach(p => {
            p.update();
            p.draw();
        });
        requestAnimationFrame(animateParticles);
    }

    window.addEventListener('resize', () => {
        resizeCanvas();
        initParticles();
    });

    initParticles();
    animateParticles();
}

// --- STATUS DA ACADEMIA ---
function checkStatus() {
    const statusText = document.getElementById('status-text');
    const statusCircle = document.getElementById('status-circle');
    
    if (!statusText || !statusCircle) return; // Segurança

    const now = new Date();
    const day = now.getDay(); 
    const hour = now.getHours();
    let aberto = false;

    if(day >= 1 && day <= 5) { if(hour >= 5 && hour < 22) aberto = true; }
    else if(day === 6) { if(hour >= 7 && hour < 12) aberto = true; }

    if(aberto) {
        statusText.textContent = "ABERTO";
        statusText.style.color = "#0f0";
        statusCircle.style.background = "#0f0";
        statusCircle.style.boxShadow = "0 0 15px rgba(0,255,0,0.7)";
    } else {
        statusText.textContent = "FECHADO";
        statusText.style.color = "#f00";
        statusCircle.style.background = "#f00";
        statusCircle.style.boxShadow = "0 0 15px rgba(255,0,0,0.7)";
    }
}
checkStatus();
setInterval(checkStatus, 60000);

// --- FEEDBACK CLIENTES (Carrossel Infinito) ---
window.addEventListener('load', () => {
    const track = document.querySelector('.clientes-track');
    if (!track) return;

    const items = Array.from(track.children);
    items.forEach(item => track.appendChild(item.cloneNode(true)));

    let scrollPos = 0;
    function step() {
        scrollPos += 0.5;
        if(scrollPos >= items[0].offsetWidth + 20) {
            track.appendChild(track.children[0]);
            scrollPos -= items[0].offsetWidth + 20;
        }
        track.style.transform = `translateX(-${scrollPos}px)`;
        requestAnimationFrame(step);
    }
    step();
});

// --- EQUIPE INTERAÇÃO ---
const equipeContent = document.querySelector('.equipe-content');
if (equipeContent) {
    let isDown = false, startX, scrollLeft;
    equipeContent.addEventListener('mousedown', (e) => {
        isDown = true;
        startX = e.pageX - equipeContent.offsetLeft;
        scrollLeft = equipeContent.scrollLeft;
    });
    equipeContent.addEventListener('mouseleave', () => isDown = false);
    equipeContent.addEventListener('mouseup', () => isDown = false);
    equipeContent.addEventListener('mousemove', (e) => {
        if(!isDown) return;
        e.preventDefault();
        const x = e.pageX - equipeContent.offsetLeft;
        const walk = (x - startX) * 2;
        equipeContent.scrollLeft = scrollLeft - walk;
    });
}

const membros = document.querySelectorAll('.membro');
membros.forEach(membro => {
    membro.addEventListener('click', () => {
        membros.forEach(m => { if (m !== membro) m.classList.remove('active'); });
        membro.classList.toggle('active');
    });
});