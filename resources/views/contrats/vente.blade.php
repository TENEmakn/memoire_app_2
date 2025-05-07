@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h2 class="h4 mb-0">Contrat de Vente de Véhicule</h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h3 class="h5 text-info">1. Parties au Contrat</h3>
                        <p>Entre les soussignés :</p>
                        <p><strong>CGV MOTORS</strong>, société de vente de véhicules, située à [Adresse], représentée par [Nom du représentant] en qualité de [Fonction], ci-après dénommée "le Vendeur"</p>
                        <p>Et</p>
                        <p>M./Mme [Nom de l'acheteur], demeurant à [Adresse], ci-après dénommé(e) "l'Acheteur"</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="h5 text-info">2. Objet du Contrat</h3>
                        <p>Le présent contrat a pour objet la vente du véhicule suivant :</p>
                        <ul>
                            <li>Marque : [Marque]</li>
                            <li>Modèle : [Modèle]</li>
                            <li>Année : [Année]</li>
                            <li>Immatriculation : [Numéro d'immatriculation]</li>
                            <li>Kilométrage : [Kilométrage] km</li>
                            <li>Numéro de série : [Numéro de série]</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="h5 text-info">3. Prix et Conditions de Paiement</h3>
                        <p>Le prix de vente est fixé à [Montant] euros.</p>
                        <p>Mode de paiement : [Mode de paiement]</p>
                        <p>Date de paiement : [Date]</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="h5 text-info">4. Garanties</h3>
                        <p>Le vendeur garantit que le véhicule :</p>
                        <ul>
                            <li>Est conforme à sa description</li>
                            <li>Est libre de tout privilège et hypothèque</li>
                            <li>Est vendu avec tous ses accessoires et documents</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="h5 text-info">4.1 Règles à adopter pour l'acheteur</h3>
                        <p>L'acheteur s'engage à :</p>
                        <ul>
                            <li>Vérifier l'état du véhicule avant la signature du contrat</li>
                            <li>Effectuer le transfert de la carte grise dans les délais légaux</li>
                            <li>Maintenir le véhicule en bon état de fonctionnement</li>
                            <li>Respecter les obligations d'assurance et de contrôle technique</li>
                            <li>Ne pas modifier les caractéristiques techniques du véhicule sans autorisation</li>
                            <li>Informer le vendeur de tout problème majeur survenant dans les 30 jours suivant l'achat</li>
                            <li>Conserver tous les documents relatifs au véhicule</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="h5 text-info">5. Transfert de Propriété</h3>
                        <p>Le transfert de propriété s'effectuera à la date de la signature du présent contrat.</p>
                        <p>L'acheteur s'engage à effectuer le transfert de la carte grise dans un délai de [Délai] jours.</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="h5 text-info">6. Signature</h3>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <p>Pour le Vendeur</p>
                                <div class="border-top pt-2">
                                    <p>Signature : _________________</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p>Pour l'Acheteur</p>
                                <div class="border-top pt-2">
                                    <p>Signature : _________________</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 