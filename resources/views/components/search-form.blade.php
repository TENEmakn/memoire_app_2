<!-- Formulaire de recherche standardisé -->
<div class="banner-search-container search-form">
    <h3 class="search-form-title"><i class="fa fa-search icon"></i>Trouvez votre véhicule idéal</h3>
    <form action="{{ route('vehicules') }}" method="GET">
        <div class="search-grid">
            <div class="form-group">
                <label for="types" class="form-label">Type de véhicule</label>
                <div class="select-wrapper">
                    <select name="types" id="types" class="form-select">
                        <option value="">Sélectionnez un type</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-car select-icon"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="makes" class="form-label">Marque</label>
                <div class="select-wrapper">
                    <select name="makes" id="makes" class="form-select">
                        <option value="">Sélectionnez une marque</option>
                        @foreach ($makes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-tag select-icon"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="series" class="form-label">Série / Modèle</label>
                <div class="select-wrapper">
                    <select name="series" id="series" class="form-select">
                        <option value="">Sélectionnez une série</option>
                    </select>
                    <i class="fa fa-list select-icon"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="years" class="form-label">Année</label>
                <div class="select-wrapper">
                    <select name="years" id="years" class="form-select">
                        <option value="">Sélectionnez une année</option>
                        @foreach ($years as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-calendar select-icon"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="transaction_types" class="form-label">Type de transaction</label>
                <div class="select-wrapper">
                    <select name="transaction_types" id="transaction_types" class="form-select">
                        <option value="">Location ou vente</option>
                        @foreach ($transaction_types as $transaction_type)
                            <option value="{{ $transaction_type->id }}">{{ $transaction_type->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-exchange select-icon"></i>
                </div>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn-search">
                <i class="fa fa-search search-icon"></i> Rechercher
            </button>
        </div>
    </form>
</div> 