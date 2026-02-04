// Menu mobile
const menuBtn = document.querySelector(".menu-btn");
const menu = document.querySelector(".menu");

menuBtn.addEventListener("click", () => {
    menu.classList.toggle("active");
});

// Canvas de partículas
const canvas = document.getElementById('canvas-bg');
const ctx = canvas.getContext('2d');

let particlesArray;

// Ajusta canvas para tamanho atual
function resizeCanvas() {
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
}
resizeCanvas();

// Classe Particle
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

        // Rebote nas bordas suavemente
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

// Inicializa partículas
function init() {
    particlesArray = [];
    for (let i = 0; i < 100; i++) { // mais partículas
        let size = Math.random() * 3 + 1.5; // um pouco maiores
        let x = Math.random() * canvas.width;
        let y = Math.random() * canvas.height;
        let speedX = (Math.random() - 0.5) * 0.3; // movimento mais suave
        let speedY = (Math.random() - 0.5) * 0.3;
        let color = Math.random() > 0.6 ? 'rgba(255,0,0,0.2)' : 'rgba(255,255,255,0.1)'; // vermelho e branco suaves
        particlesArray.push(new Particle(x, y, size, color, speedX, speedY));
    }
}

// Animação das partículas
function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    particlesArray.forEach(p => {
        p.update();
        p.draw();
    });
    requestAnimationFrame(animate);
}

// Redimensionamento da tela
window.addEventListener('resize', () => {
    resizeCanvas();
    init();
});

// Inicializa partículas
init();
animate();





// Scroll suave horizontal no mobile para o carrossel da equipe
const equipeContent = document.querySelector('.equipe-content');

let isDown = false;
let startX;
let scrollLeft;

equipeContent.addEventListener('mousedown', (e) => {
    isDown = true;
    equipeContent.classList.add('active');
    startX = e.pageX - equipeContent.offsetLeft;
    scrollLeft = equipeContent.scrollLeft;
});

equipeContent.addEventListener('mouseleave', () => {
    isDown = false;
    equipeContent.classList.remove('active');
});

equipeContent.addEventListener('mouseup', () => {
    isDown = false;
    equipeContent.classList.remove('active');
});

equipeContent.addEventListener('mousemove', (e) => {
    if(!isDown) return;
    e.preventDefault();
    const x = e.pageX - equipeContent.offsetLeft;
    const walk = (x - startX) * 2; // velocidade do scroll
    equipeContent.scrollLeft = scrollLeft - walk;
});

// Expansão individual dos membros da equipe
const membros = document.querySelectorAll('.membro');

membros.forEach(membro => {
    membro.addEventListener('click', () => {
        // Fecha todos os outros membros
        membros.forEach(m => {
            if (m !== membro) m.classList.remove('active');
        });

        // Alterna apenas o membro clicado
        membro.classList.toggle('active');
    });
});






window.addEventListener('load', () => {
    const track = document.querySelector('.clientes-track');
    const speed = 0.5; // velocidade de scroll, ajuste se quiser mais rápido
    const items = Array.from(track.children);

    // Clona os itens suficientes para cobrir a tela e mais um pouco
    items.forEach(item => track.appendChild(item.cloneNode(true)));

    let scrollPos = 0;

    function animate() {
        scrollPos += speed;
        if(scrollPos >= items[0].offsetWidth + 20) { // 20 = gap do CSS
            // Move o primeiro item pro final do track
            track.appendChild(track.children[0]);
            scrollPos -= items[0].offsetWidth + 20;
        }

        track.style.transform = `translateX(-${scrollPos}px)`;
        requestAnimationFrame(animate);
    }

    animate();
});






function checkStatus() {
    const statusText = document.getElementById('status-text');
    const statusCircle = document.getElementById('status-circle');
    const now = new Date();
    const day = now.getDay(); // 0=Dom, 1=Seg ... 6=Sáb
    const hour = now.getHours();

    let aberto = false;

    if(day >= 1 && day <= 5){ // Seg-Sex 5h-22h
        if(hour >=5 && hour < 22) aberto = true;
    } else if(day === 6){ // Sábado 7h-12h
        if(hour >=7 && hour < 12) aberto = true;
    }

    if(aberto){
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

