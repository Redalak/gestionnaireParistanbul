window.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.stats-card');
    cards.forEach((card, i) => {
        setTimeout(() => card.classList.add('visible'), i * 150);

        const p = card.querySelector('p');
        let target = parseFloat(p.textContent.replace(/\s|€/g,'')) || 0;
        let count = 0;
        const increment = target / 100;
        const interval = setInterval(() => {
            count += increment;
            if(count >= target){
                p.textContent = p.textContent.includes('€')
                    ? target.toLocaleString('fr-FR',{minimumFractionDigits:2})+' €'
                    : Math.floor(target);
                clearInterval(interval);
            } else {
                p.textContent = p.textContent.includes('€')
                    ? count.toLocaleString('fr-FR',{minimumFractionDigits:2})+' €'
                    : Math.floor(count);
            }
        }, 10);
    });
});
