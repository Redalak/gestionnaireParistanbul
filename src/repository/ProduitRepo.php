<?php

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../model/Produit.php';

use bdd\Bdd;
use model\Produit;

class ProduitRepo
{
    // --- Ajouter un produit ---
    public function ajoutProduit(Produit $produit) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('
            INSERT INTO produit (
                libelle, marque, origine, ref_sous_categorie, ref_categorie, 
                reference_produit, code_barre, unite_mesure, unite_ou_pack, 
                nb_unite_pack, bio, halal, vegan, prix_unitaire
            )
            VALUES (
                :libelle, :marque, :origine, :ref_sous_categorie, :ref_categorie, 
                :reference_produit, :code_barre, :unite_mesure, :unite_ou_pack, 
                :nb_unite_pack, :bio, :halal, :vegan, :prix_unitaire
            )
        ');

        $req->execute([
            'libelle'            => $produit->getLibelle(),
            'marque'             => $produit->getMarque(),
            'origine'            => $produit->getOrigine(),
            'ref_sous_categorie' => $produit->getRefSousCategorie(),
            'ref_categorie'      => $produit->getRefCategorie(),
            'reference_produit'  => $produit->getReferenceProduit(),
            'code_barre'         => $produit->getCodeBarre(),
            'unite_mesure'       => $produit->getUniteMesure(),
            'unite_ou_pack'      => $produit->getUniteOuPack(),
            'nb_unite_pack'      => $produit->getNbUnitePack(),
            'bio'                => $produit->getBio(),
            'halal'              => $produit->getHalal(),
            'vegan'              => $produit->getVegan(),
            'prix_unitaire'      => $produit->getPrixUnitaire()
        ]);

        return $produit;
    }

    // --- Modifier un produit ---
    public function modifProduit(Produit $produit) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('
            UPDATE produit SET
                libelle = :libelle,
                marque = :marque,
                origine = :origine,
                ref_sous_categorie = :ref_sous_categorie,
                ref_categorie = :ref_categorie,
                reference_produit = :reference_produit,
                code_barre = :code_barre,
                unite_mesure = :unite_mesure,
                unite_ou_pack = :unite_ou_pack,
                nb_unite_pack = :nb_unite_pack,
                bio = :bio,
                halal = :halal,
                vegan = :vegan,
                prix_unitaire = :prix_unitaire
            WHERE id_produit = :id_produit
        ');

        $req->execute([
            'id_produit'         => $produit->getIdProduit(),
            'libelle'            => $produit->getLibelle(),
            'marque'             => $produit->getMarque(),
            'origine'            => $produit->getOrigine(),
            'ref_sous_categorie' => $produit->getRefSousCategorie(),
            'ref_categorie'      => $produit->getRefCategorie(),
            'reference_produit'  => $produit->getReferenceProduit(),
            'code_barre'         => $produit->getCodeBarre(),
            'unite_mesure'       => $produit->getUniteMesure(),
            'unite_ou_pack'      => $produit->getUniteOuPack(),
            'nb_unite_pack'      => $produit->getNbUnitePack(),
            'bio'                => $produit->getBio(),
            'halal'              => $produit->getHalal(),
            'vegan'              => $produit->getVegan(),
            'prix_unitaire'      => $produit->getPrixUnitaire()
        ]);

        return $produit;
    }

    // --- Supprimer un produit ---
    public function suppProduit(int $idProduit) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('DELETE FROM produit WHERE id_produit = :id_produit');
        $req->execute(['id_produit' => $idProduit]);
    }

    // --- Lister tous les produits ---
    public function listeProduits() {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->query('SELECT * FROM produit ORDER BY id_produit DESC');
        $rows = $req->fetchAll(\PDO::FETCH_ASSOC);

        $produits = [];
        foreach ($rows as $row) {
            $produits[] = new Produit($row);
        }
        return $produits;
    }

    // --- Récupérer les derniers produits ajoutés ---
    public function getDerniersProduits(int $limit = 5) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $stmt = $database->prepare('SELECT * FROM produit ORDER BY id_produit DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $produits = [];
        foreach ($rows as $row) {
            $produits[] = new Produit($row);
        }
        return $produits;
    }

    // --- Compter le nombre total de produits ---
    public function nbProduits(): int {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->query('SELECT COUNT(*) as total FROM produit');
        $result = $req->fetch(\PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    // --- Récupérer un produit par ID ---
    public function getProduitParId(int $idProduit): ?Produit {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('SELECT * FROM produit WHERE id_produit = :id_produit');
        $req->execute(['id_produit' => $idProduit]);
        $row = $req->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new Produit($row);
        }
        return null;
    }

    // --- Rechercher un produit par libellé ---
    public function getProduitsParLibelle(string $libelle) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('SELECT * FROM produit WHERE libelle LIKE :libelle');
        $req->execute(['libelle' => '%' . $libelle . '%']);
        $rows = $req->fetchAll(\PDO::FETCH_ASSOC);

        $produits = [];
        foreach ($rows as $row) {
            $produits[] = new Produit($row);
        }
        return $produits;
    }

    // --- Bonus : filtrer par catégorie ---
    public function getProduitsParCategorie(int $refCategorie) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('SELECT * FROM produit WHERE ref_categorie = :ref_categorie');
        $req->execute(['ref_categorie' => $refCategorie]);
        $rows = $req->fetchAll(\PDO::FETCH_ASSOC);

        $produits = [];
        foreach ($rows as $row) {
            $produits[] = new Produit($row);
        }
        return $produits;
    }
}
