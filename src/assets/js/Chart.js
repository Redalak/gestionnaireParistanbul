// statistiques.js

// --- Gestion sidebar ---
const sidebar = document.querySelector('.sidebar');
const toggleBtn = document.querySelector('.sidebar-toggler');
const mobileBtn = document.querySelector('.sidebar-menu-button');

toggleBtn.addEventListener('click', () => { sidebar.classList.toggle('collapsed'); });
mobileBtn.addEventListener('click', () => { sidebar.classList.toggle('collapsed'); });

// --- Graphiques ---
// Les données PHP seront injectées dans le HTML via des data attributes ou variables JS

function renderCharts(data) {
    // Commandes par magasin
    new Chart(document.getElementById('commandeChart').getContext('2d'), {
        type:'bar',
        data: {
            labels: data.commandeParMagasin.map(d => d.nom), // <-- ici
            datasets:[{
                label:'Commandes',
                data: data.commandeParMagasin.map(d => d.nb_commandes),
                backgroundColor:'#151A2D',
                borderRadius:6
            }]
        },
        options:{
            responsive:true,
            plugins:{legend:{display:false}},
            scales:{
                x:{ticks:{color:'#0f1330'}},
                y:{ticks:{color:'#0f1330'}}
            }
        }
    });

    // Commandes du mois
    new Chart(document.getElementById('commandesMoisChart').getContext('2d'), {
        type:'line',
        data:{labels:data.commandesMois.map(d=>d.jour), datasets:[{label:'Commandes', data:data.commandesMois.map(d=>d.nb_commandes), borderColor:'#151A2D', backgroundColor:'rgba(21,26,45,0.1)', fill:true, tension:0.3}]},
        options:{responsive:true, plugins:{legend:{display:false}}}
    });

    // Commandes par client
    new Chart(document.getElementById('commandeClientChart').getContext('2d'), {
        type:'bar',
        data:{labels:data.commandeParClient.map(d=>d.client), datasets:[{label:'Commandes', data:data.commandeParClient.map(d=>d.nb_commandes), backgroundColor:'#4CAF50'}]},
        options:{indexAxis:'y', responsive:true, plugins:{legend:{display:false}}}
    });

    // Top produits vendus
    new Chart(document.getElementById('topProduitsChart').getContext('2d'), {
        type: 'bar',
        data: { labels: data.topProduits.map(d=>d.produit), datasets: [{ label: 'Quantité vendue', data: data.topProduits.map(d=>d.quantite), backgroundColor: '#FF9800' }]},
        options: { indexAxis: 'y', responsive:true, plugins:{legend:{display:false}} }
    });

    // État des commandes
    const colorsEtat = {'en attente':'#FFD700','préparée':'#0f1330','expédiée':'#2a3150','livrée':'#4CAF50','annulée':'#FF4C4C'};
    new Chart(document.getElementById('etatChart').getContext('2d'), {
        type:'pie',
        data:{labels:data.etatCommandes.map(d=>d.etat), datasets:[{data:data.etatCommandes.map(d=>d.nb_commandes), backgroundColor:data.etatCommandes.map(d=>colorsEtat[d.etat]||'#CBD4FF')}]},
        options:{responsive:true, plugins:{legend:{position:'bottom', labels:{font:{size:14,weight:'600'}, color:'#151A2D'}}}}
    });

    // Produits par catégorie
    new Chart(document.getElementById('categorieChart').getContext('2d'), {
        type:'doughnut',
        data:{labels:data.produitsParCategorie.map(d=>d.categorie), datasets:[{data:data.produitsParCategorie.map(d=>d.nb_produits), backgroundColor:['#FF9800','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40']}]},
        options:{responsive:true, plugins:{legend:{position:'bottom', labels:{font:{size:14,weight:'600'}, color:'#151A2D'}}}}
    });
}
