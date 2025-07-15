@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h2 class="h4 mb-0">Contrat de Location de Véhicule</h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h3 class="h5 text-info">1. Parties au Contrat</h3>
                        <p>Entre les soussignés :</p>
                        <p><strong>CGV Motors</strong>, société de location de véhicules, située à [Adresse], représentée par [Nom du représentant] en qualité de [Fonction], ci-après dénommée "le Loueur"</p>
                        <p>Et</p>
                        <p>M./Mme [Nom du locataire], demeurant à [Adresse], ci-après dénommé(e) "le Locataire"</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="h5 text-info">2. Objet du Contrat</h3>
                        <p>Le présent contrat a pour objet la location du véhicule suivant :</p>
                        <ul>
                            <li>Marque : [Marque]</li>
                            <li>Modèle : [Modèle]</li>
                            <li>Immatriculation : [Numéro d'immatriculation]</li>
                            <li>Kilométrage initial : [Kilométrage] km</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="h5 text-info">3. Durée et Conditions de Location</h3>
                        <p>La durée de location est fixée à [Durée] à compter du [Date de début].</p>
                        <p>Le prix de la location est fixé à [Montant] par jour, soit un total de [Montant total].</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="h5 text-info">4. Obligations du Locataire</h3>
                        <ul>
                            <li>Utiliser le véhicule conformément à sa destination</li>
                            <li>Effectuer les entretiens courants</li>
                            <li>Ne pas sous-louer le véhicule</li>
                            <li>Restituer le véhicule dans l'état où il l'a reçu</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="h5 text-info">5. Assurance</h3>
                        <p>Le véhicule est assuré pour les risques suivants :</p>
                        <ul>
                            <li>Responsabilité civile</li>
                            <li>Dommages collision</li>
                            <li>Vol et incendie</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="h5 text-info">6. Signature</h3>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <p>Pour le Loueur</p>
                                <div class="border-top pt-2">
                                    <p>Signature : _________________</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p>Pour le Locataire</p>
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