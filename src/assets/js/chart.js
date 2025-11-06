window.addEventListener('DOMContentLoaded', () => {
    const data = window.statsData;

    function generateColors(n){
        return Array.from({length:n}, (_,i)=>`hsl(${i*360/n}, 70%, 60%)`);
    }

    // Commandes par magasin
    new Chart(document.getElementById('commandeChart'), {
        type: 'bar',
        data: {
            labels: data.commandeParMagasin.map(d=>d.magasin),
            datasets:[{label:'Commandes', data:data.commandeParMagasin.map(d=>d.nb_commandes), backgroundColor:'#ff6b35'}]
        },
        options:{responsive:true, plugins:{tooltip:{enabled:true}}, scales:{y:{beginAtZero:true}}}
    });

    // Commandes du mois
    new Chart(document.getElementById('commandesMoisChart'), {
        type:'line',
        data:{
            labels:data.commandesMois.map(d=>d.date),
            datasets:[{
                label:'Commandes',
                data:data.commandesMois.map(d=>d.nb_commandes),
                borderColor:'#42a5f5',
                backgroundColor:'rgba(66,165,245,0.2)',
                fill:true,
                tension:0.3
            }]
        },
        options:{
            responsive:true,
            plugins:{tooltip:{enabled:true}},
            scales:{y:{beginAtZero:true}}
        }
    });

    // Commandes par client
    new Chart(document.getElementById('commandeClientChart'), {
        type:'bar',
        data:{
            labels:data.commandeParClient.map(d=>d.client),
            datasets:[{label:'Commandes', data:data.commandeParClient.map(d=>d.nb_commandes), backgroundColor:'#66bb6a'}]
        },
        options:{responsive:true, scales:{y:{beginAtZero:true}}}
    });

    // Top produits
    new Chart(document.getElementById('topProduitsChart'), {
        type:'bar',
        data:{
            labels:data.topProduits.map(d=>d.produit),
            datasets:[{label:'Ventes', data:data.topProduits.map(d=>d.quantite), backgroundColor:'#ffb74d'}]
        },
        options:{responsive:true, scales:{y:{beginAtZero:true}}}
    });

    // État des commandes
    const etatColors = generateColors(data.etatCommandes.length);
    new Chart(document.getElementById('etatChart'), {
        type:'doughnut',
        data:{labels:data.etatCommandes.map(d=>d.etat), datasets:[{data:data.etatCommandes.map(d=>d.nb_commandes), backgroundColor:etatColors}]},
        options:{responsive:true, plugins:{tooltip:{enabled:true}}}
    });

    // Produits par catégorie
    const catColors = generateColors(data.produitsParCategorie.length);
    new Chart(document.getElementById('categorieChart'), {
        type:'pie',
        data:{labels:data.produitsParCategorie.map(d=>d.nom_categorie), datasets:[{data:data.produitsParCategorie.map(d=>d.quantite_totale), backgroundColor:catColors}]},
        options:{responsive:true, plugins:{tooltip:{enabled:true}}}
    });
});
