document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les champs du formulaire et les éléments d'interface
    const marqueSelect = document.getElementById('marque');
    const serieSelect = document.getElementById('serie');
    const typeSelect = document.getElementById('type');
    const anneeSelect = document.getElementById('annee');
    const typeAnnonceSelect = document.getElementById('type-annonce');
    const searchButton = document.querySelector('.btn-search');
    const searchForm = document.getElementById('vehicle-search-form');
    const searchGrid = document.querySelector('.search-grid');
    const filterToggleBtn = document.querySelector('.filter-toggle-button');
    const toggleIcon = document.querySelector('.toggle-icon');
    const activeFiltersTags = document.querySelector('.active-filters-tags');
    const quickFilterBtns = document.querySelectorAll('.quick-filter-btn');
    
    // Ces éléments n'existent plus dans la nouvelle structure
    // const advancedSearchBtn = document.querySelector('.advanced-search-btn');
    // const advancedSearchPanel = document.querySelector('.advanced-search-panel');
    
    // Variables pour la gestion des états
    let requestTimeout = null;
    const DELAY = 300; // Délai de 300ms entre les requêtes
    let isAdvancedFilterVisible = true;
    
    // Créer un élément pour afficher le nombre de résultats
    const resultCountElement = document.createElement('div');
    resultCountElement.className = 'result-count';
    document.querySelector('.search-button').insertBefore(resultCountElement, searchButton);
    
    // Ajouter un bouton de réinitialisation des filtres
    const resetButton = document.createElement('button');
    resetButton.type = 'button';
    resetButton.className = 'reset-filters';
    resetButton.innerHTML = '<i class="fas fa-sync-alt"></i> Réinitialiser les filtres';
    resetButton.style.display = 'none';
    document.querySelector('.search-filters-toggle').appendChild(resetButton);
    
    // Gestion du toggle pour les filtres avancés
    filterToggleBtn.addEventListener('click', function() {
        isAdvancedFilterVisible = !isAdvancedFilterVisible;
        searchGrid.classList.toggle('collapsed', !isAdvancedFilterVisible);
        toggleIcon.classList.toggle('rotate', !isAdvancedFilterVisible);
    });
    
    // Mise à jour des filtres rapides actifs
    function updateQuickFilterUI() {
        quickFilterBtns.forEach(btn => {
            const targetField = btn.dataset.target;
            const targetValue = btn.dataset.value;
            const isActive = document.querySelector(`select[name="${targetField}"]`).value === targetValue;
            btn.classList.toggle('active', isActive);
        });
    }
    
    // Écouteurs d'événements pour les filtres rapides
    quickFilterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetField = this.dataset.target;
            const targetValue = this.dataset.value;
            const targetSelect = document.querySelector(`select[name="${targetField}"]`);
            
            if (targetSelect.value === targetValue) {
                // Désactiver le filtre
                targetSelect.selectedIndex = 0;
                
                // Vérifier si tous les filtres sont désactivés après cette action
                const allFiltersEmpty = selects.every(select => !select.value);
                if (allFiltersEmpty) {
                    // Rediriger vers la page d'index sans paramètres
                    window.location.href = indexRoute;
                    return;
                }
            } else {
                // Activer le filtre
                Array.from(targetSelect.options).forEach(option => {
                    if (option.value === targetValue) {
                        option.selected = true;
                    }
                });
            }
            
            // Déclencher l'événement change pour mettre à jour les filtres
            const event = new Event('change');
            targetSelect.dispatchEvent(event);
            
            // Mettre à jour l'interface des filtres rapides
            updateQuickFilterUI();
        });
    });
    
    // Fonction pour mettre à jour l'affichage des tags des filtres actifs
    function updateActiveFiltersTags() {
        activeFiltersTags.innerHTML = '';
        
        const selectedFilters = [
            { select: marqueSelect, icon: 'fa-car', label: 'Marque' },
            { select: serieSelect, icon: 'fa-tag', label: 'Série' },
            { select: typeSelect, icon: 'fa-truck-pickup', label: 'Type' },
            { select: anneeSelect, icon: 'fa-calendar-alt', label: 'Année' },
            { select: typeAnnonceSelect, icon: 'fa-tags', label: 'Type' }
        ];
        
        let hasActiveFilters = false;
        
        selectedFilters.forEach(filter => {
            if (filter.select && filter.select.value) {
                hasActiveFilters = true;
                const tag = document.createElement('div');
                tag.className = 'filter-tag';
                tag.innerHTML = `
                    <i class="fas ${filter.icon}"></i>
                    <span>${filter.label}: ${filter.select.options[filter.select.selectedIndex].text}</span>
                    <span class="remove-tag" data-target="${filter.select.id}">
                        <i class="fas fa-times"></i>
                    </span>
                `;
                activeFiltersTags.appendChild(tag);
                
                // Ajouter un écouteur pour supprimer le filtre
                tag.querySelector('.remove-tag').addEventListener('click', function() {
                    const targetId = this.dataset.target;
                    document.getElementById(targetId).selectedIndex = 0;
                    
                    // Déclencher l'événement change
                    const event = new Event('change');
                    document.getElementById(targetId).dispatchEvent(event);
                });
            }
        });
        
        // Afficher ou masquer le bouton de réinitialisation
        resetButton.style.display = hasActiveFilters ? 'inline-flex' : 'none';
    }
    
    // Fonction pour gérer l'état de chargement des selects
    function setLoadingState(isLoading) {
        const selects = [marqueSelect, serieSelect, typeSelect, anneeSelect, typeAnnonceSelect];
        selects.forEach(select => {
            if (select) {
                select.classList.toggle('loading', isLoading);
                select.disabled = isLoading;
            }
        });
        
        if (isLoading) {
            searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Chargement...';
            searchButton.disabled = true;
        } else {
            searchButton.innerHTML = '<i class="fas fa-search search-icon"></i> Rechercher un véhicule';
            searchButton.disabled = false;
        }
    }
    
    // Écouteurs d'événements pour les selects
    const selects = [marqueSelect, serieSelect, typeSelect, anneeSelect, typeAnnonceSelect];
    
    selects.forEach(select => {
        if (select) {
            select.addEventListener('change', function() {
                // Mise à jour des tags de filtres actifs
                updateActiveFiltersTags();
                
                // Mise à jour de l'UI des filtres rapides
                updateQuickFilterUI();
                
                // Annuler la requête précédente si elle existe
                if (requestTimeout) {
                    clearTimeout(requestTimeout);
                }
                
                // Définir un délai avant de lancer la requête
                requestTimeout = setTimeout(() => {
                    updateFilterOptions(this.id);
                }, DELAY);
            });
        }
    });
    
    // Gestionnaire de réinitialisation des filtres
    resetButton.addEventListener('click', function() {
        // Rediriger vers la page d'index sans paramètres
        window.location.href = indexRoute;
    });
    
    // Fonction pour mettre à jour les options des champs de filtre
    function updateFilterOptions(changedFieldId) {
        // Récupérer les valeurs actuelles de tous les champs
        const filters = {
            marque: marqueSelect ? marqueSelect.value : '',
            serie: serieSelect ? serieSelect.value : '',
            type: typeSelect ? typeSelect.value : '',
            annee: anneeSelect ? anneeSelect.value : '',
            type_annonce: typeAnnonceSelect ? typeAnnonceSelect.value : '',
            exclude_field: changedFieldId // Le champ qui a changé ne doit pas être mis à jour
        };
        
        // Vérifier si tous les champs sont vides (sauf pour le cas de réinitialisation)
        const allEmpty = Object.keys(filters).every(key => 
            key === 'exclude_field' || !filters[key]
        );
        
        if (allEmpty && changedFieldId !== 'reset') {
            updateActiveFiltersTags();
            updateQuickFilterUI();
            resultCountElement.textContent = '';
            return;
        }
        
        // Afficher l'état de chargement
        setLoadingState(true);
        
        // Construire l'URL avec les paramètres
        const url = new URL(filterOptionsRoute);
        Object.keys(filters).forEach(key => {
            if (filters[key]) {
                url.searchParams.append(key, filters[key]);
            }
        });
        
        // Effectuer la requête AJAX
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'error') {
                    throw new Error(data.message);
                }
                
                // Mettre à jour le compteur de résultats avec une animation
                if (data.count > 0) {
                    resultCountElement.innerHTML = `<i class="fas fa-car"></i> ${data.count} véhicule(s) trouvé(s)`;
                    resultCountElement.style.color = '#28a745';
                } else {
                    resultCountElement.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Aucun véhicule ne correspond à ces critères';
                    resultCountElement.style.color = '#dc3545';
                }
                
                // Mettre à jour les options des sélects si le champ n'est pas celui qui a changé
                // ou si on réinitialise tous les filtres
                if ((changedFieldId !== 'marque' && changedFieldId !== 'reset') || 
                    (changedFieldId === 'reset' && data.marques && data.marques.length > 0)) {
                    updateSelectOptions(marqueSelect, data.marques, filters.marque);
                }
                
                if ((changedFieldId !== 'serie' && changedFieldId !== 'reset') || 
                    (changedFieldId === 'reset' && data.series && data.series.length > 0)) {
                    updateSelectOptions(serieSelect, data.series, filters.serie);
                }
                
                if ((changedFieldId !== 'type' && changedFieldId !== 'reset') || 
                    (changedFieldId === 'reset' && data.types && data.types.length > 0)) {
                    updateSelectOptions(typeSelect, data.types, filters.type);
                }
                
                if ((changedFieldId !== 'annee' && changedFieldId !== 'reset') || 
                    (changedFieldId === 'reset' && data.annees && data.annees.length > 0)) {
                    updateSelectOptions(anneeSelect, data.annees, filters.annee);
                }
                
                // Mettre à jour l'interface utilisateur
                updateActiveFiltersTags();
                updateQuickFilterUI();
            })
            .catch(error => {
                console.error('Erreur lors de la mise à jour des filtres:', error);
                // Afficher un message d'erreur
                resultCountElement.innerHTML = '<i class="fas fa-exclamation-circle"></i> Erreur lors de la mise à jour des filtres';
                resultCountElement.style.color = '#dc3545';
            })
            .finally(() => {
                // Désactiver l'état de chargement
                setLoadingState(false);
            });
    }
    
    // Fonction pour mettre à jour les options d'un select
    function updateSelectOptions(selectElement, options, selectedValue) {
        if (!selectElement || !options) return;
        
        // Sauvegarder l'option par défaut
        const defaultOption = selectElement.querySelector('option[value=""]');
        
        // Vider le select
        selectElement.innerHTML = '';
        
        // Ajouter l'option par défaut
        if (defaultOption) {
            selectElement.appendChild(defaultOption);
        }
        
        // Ajouter les nouvelles options avec une animation d'entrée progressive
        options.forEach((option, index) => {
            if (option) { // Vérifier que l'option n'est pas vide
                const optionElement = document.createElement('option');
                optionElement.value = option;
                optionElement.textContent = option;
                
                if (option === selectedValue) {
                    optionElement.selected = true;
                }
                
                selectElement.appendChild(optionElement);
            }
        });
        
        // Activer/désactiver le select en fonction du nombre d'options
        selectElement.disabled = options.length === 0;
        
        // Ajouter un effet visuel pour montrer que le contenu a été mis à jour
        selectElement.classList.add('updated');
        setTimeout(() => {
            selectElement.classList.remove('updated');
        }, 500);
    }
    
    // Initialiser les filtres au chargement de la page si des paramètres sont présents dans l'URL
    const urlParams = new URLSearchParams(window.location.search);
    let hasFilters = false;
    
    selects.forEach(select => {
        if (select) {
            const param = urlParams.get(select.name);
            if (param) {
                hasFilters = true;
                // Trouver et sélectionner l'option correspondante
                Array.from(select.options).forEach(option => {
                    if (option.value === param) {
                        option.selected = true;
                    }
                });
            }
        }
    });
    
    if (hasFilters) {
        // Mettre à jour l'interface utilisateur avec les filtres de l'URL
        updateActiveFiltersTags();
        updateQuickFilterUI();
        
        // Mettre à jour les filtres en fonction des paramètres de l'URL
        updateFilterOptions('url');
    }
    
    // Initialiser l'état initial de l'interface
    updateActiveFiltersTags();
    updateQuickFilterUI();
    
    // Amélioration: Ajouter la recherche instantanée
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            // Éviter de soumettre le formulaire si aucun filtre n'est sélectionné
            const hasActiveFilters = selects.some(select => select && select.value !== '');
            
            if (!hasActiveFilters) {
                e.preventDefault();
                resultCountElement.innerHTML = '<i class="fas fa-info-circle"></i> Veuillez sélectionner au moins un critère de recherche';
                resultCountElement.style.color = '#17a2b8';
                
                // Animation de secousse pour indiquer qu'une action est nécessaire
                searchGrid.classList.add('shake');
                setTimeout(() => {
                    searchGrid.classList.remove('shake');
                }, 500);
                return;
            }
            
            // Si le compteur indique qu'aucun résultat ne sera trouvé, prévenir l'utilisateur
            if (resultCountElement.textContent.includes('Aucun véhicule')) {
                if (!confirm('Aucun véhicule ne correspond à ces critères. Souhaitez-vous quand même effectuer la recherche?')) {
                    e.preventDefault();
                    return;
                }
            }

            // Stocker le type d'annonce sélectionné dans localStorage pour y accéder après le rechargement de la page
            const typeAnnonce = typeAnnonceSelect ? typeAnnonceSelect.value : '';
            if (typeAnnonce) {
                localStorage.setItem('scrollToSection', typeAnnonce);
            } else {
                // Si aucun type d'annonce n'est sélectionné, défiler par défaut vers la section location
                localStorage.setItem('scrollToSection', 'location');
            }
        });
    }
    
    // Gestion des recherches récentes dans le localStorage
    const MAX_RECENT_SEARCHES = 5;
    
    // Fonction pour sauvegarder la recherche actuelle
    function saveCurrentSearch() {
        const currentFilters = {};
        selects.forEach(select => {
            if (select && select.value) {
                currentFilters[select.name] = {
                    value: select.value,
                    text: select.options[select.selectedIndex].text
                };
            }
        });
        
        // Ne pas sauvegarder si aucun filtre n'est actif
        if (Object.keys(currentFilters).length === 0) return;
        
        try {
            // Récupérer les recherches existantes
            let recentSearches = JSON.parse(localStorage.getItem('recentVehicleSearches') || '[]');
            
            // Vérifier si cette recherche existe déjà (pour éviter les doublons)
            const searchExists = recentSearches.some(search => {
                return JSON.stringify(search) === JSON.stringify(currentFilters);
            });
            
            if (!searchExists) {
                // Ajouter la recherche actuelle
                recentSearches.unshift(currentFilters);
                
                // Limiter le nombre de recherches récentes
                if (recentSearches.length > MAX_RECENT_SEARCHES) {
                    recentSearches = recentSearches.slice(0, MAX_RECENT_SEARCHES);
                }
                
                // Sauvegarder
                localStorage.setItem('recentVehicleSearches', JSON.stringify(recentSearches));
                
                // Mettre à jour l'interface des recherches récentes si elle existe
                updateRecentSearchesUI();
            }
        } catch (e) {
            console.error('Erreur lors de la sauvegarde de la recherche:', e);
        }
    }
    
    // Créer un conteneur pour les recherches récentes
    if (searchForm) {
        const recentSearchesContainer = document.createElement('div');
        recentSearchesContainer.className = 'recent-searches';
        recentSearchesContainer.innerHTML = '<h4><i class="fas fa-history"></i> Recherches récentes</h4><div class="recent-searches-list"></div>';
        searchForm.appendChild(recentSearchesContainer);
        
        // Fonction pour mettre à jour l'interface des recherches récentes
        function updateRecentSearchesUI() {
            try {
                const recentSearches = JSON.parse(localStorage.getItem('recentVehicleSearches') || '[]');
                const recentSearchesList = recentSearchesContainer.querySelector('.recent-searches-list');
                
                if (recentSearches.length === 0) {
                    recentSearchesContainer.style.display = 'none';
                    return;
                }
                
                recentSearchesContainer.style.display = 'block';
                recentSearchesList.innerHTML = '';
                
                recentSearches.forEach((search, index) => {
                    const searchItem = document.createElement('div');
                    searchItem.className = 'recent-search-item';
                    
                    // Créer le contenu de l'élément
                    let searchContent = '';
                    Object.keys(search).forEach(key => {
                        const icon = key === 'marque' ? 'fa-car' : 
                                    key === 'serie' ? 'fa-tag' : 
                                    key === 'type' ? 'fa-truck-pickup' : 
                                    key === 'annee' ? 'fa-calendar-alt' : 
                                    key === 'type_annonce' ? 'fa-tags' : 'fa-filter';
                        
                        searchContent += `<span class="search-item-tag"><i class="fas ${icon}"></i> ${search[key].text}</span>`;
                    });
                    
                    searchItem.innerHTML = `
                        <div class="search-item-content">${searchContent}</div>
                        <div class="search-item-actions">
                            <button type="button" class="apply-search" data-index="${index}">
                                <i class="fas fa-search"></i>
                            </button>
                            <button type="button" class="remove-search" data-index="${index}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    
                    recentSearchesList.appendChild(searchItem);
                    
                    // Ajouter les écouteurs d'événements
                    searchItem.querySelector('.apply-search').addEventListener('click', function() {
                        applyRecentSearch(index);
                    });
                    
                    searchItem.querySelector('.remove-search').addEventListener('click', function() {
                        removeRecentSearch(index);
                    });
                });
            } catch (e) {
                console.error('Erreur lors de la mise à jour des recherches récentes:', e);
            }
        }
        
        // Fonction pour appliquer une recherche récente
        function applyRecentSearch(index) {
            try {
                const recentSearches = JSON.parse(localStorage.getItem('recentVehicleSearches') || '[]');
                const search = recentSearches[index];
                
                if (!search) return;
                
                // Réinitialiser tous les champs d'abord
                selects.forEach(select => {
                    if (select) {
                        select.selectedIndex = 0;
                    }
                });
                
                // Appliquer les filtres de la recherche récente
                Object.keys(search).forEach(key => {
                    const select = document.querySelector(`select[name="${key}"]`);
                    if (select) {
                        Array.from(select.options).forEach(option => {
                            if (option.value === search[key].value) {
                                option.selected = true;
                            }
                        });
                        
                        // Déclencher l'événement change
                        const event = new Event('change');
                        select.dispatchEvent(event);
                    }
                });
                
                // Mettre à jour l'interface
                updateActiveFiltersTags();
                updateQuickFilterUI();
                
                // Stocker le type d'annonce sélectionné dans localStorage pour le scroll après rechargement
                if (search.type_annonce && search.type_annonce.value) {
                    localStorage.setItem('scrollToSection', search.type_annonce.value);
                } else {
                    // Si aucun type d'annonce n'est sélectionné, défiler par défaut vers la section location
                    localStorage.setItem('scrollToSection', 'location');
                }
                
                // Soumettre automatiquement le formulaire après avoir appliqué les filtres
                if (searchForm) {
                    searchForm.submit();
                }
            } catch (e) {
                console.error('Erreur lors de l\'application de la recherche récente:', e);
            }
        }
        
        // Fonction pour supprimer une recherche récente
        function removeRecentSearch(index) {
            try {
                const recentSearches = JSON.parse(localStorage.getItem('recentVehicleSearches') || '[]');
                recentSearches.splice(index, 1);
                localStorage.setItem('recentVehicleSearches', JSON.stringify(recentSearches));
                
                // Si c'était la dernière recherche récente, vérifier si des filtres sont actifs
                if (recentSearches.length === 0) {
                    const allFiltersEmpty = selects.every(select => !select || !select.value);
                    if (!allFiltersEmpty) {
                        // S'il y a des filtres actifs, rediriger vers la page d'index
                        window.location.href = indexRoute;
                        return;
                    }
                }
                
                updateRecentSearchesUI();
            } catch (e) {
                console.error('Erreur lors de la suppression de la recherche récente:', e);
            }
        }
        
        // Sauvegarder la recherche lorsque l'utilisateur soumet le formulaire
        searchForm.addEventListener('submit', saveCurrentSearch);
        
        // Initialiser l'interface des recherches récentes
        updateRecentSearchesUI();
    }
    
    // Gérer le défilement automatique vers la section appropriée après chargement de la page
    window.addEventListener('load', function() {
        const scrollToSection = localStorage.getItem('scrollToSection');
        if (scrollToSection) {
            // Déterminer la section cible en fonction du type d'annonce
            let targetSection;
            if (scrollToSection === 'location') {
                targetSection = document.getElementById('vehicules-location');
            } else if (scrollToSection === 'vente') {
                targetSection = document.getElementById('vehicules-vente');
            }
            
            if (targetSection) {
                // Faire défiler jusqu'à la section avec un délai pour assurer que tout est chargé
                setTimeout(function() {
                    window.scrollTo({
                        top: targetSection.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }, 500);
            }
            
            // Supprimer l'information du localStorage après utilisation
            localStorage.removeItem('scrollToSection');
        }
    });
    
    // Animation avancée pour les selects lorsqu'ils changent de valeur
    selects.forEach(select => {
        if (select) {
            select.addEventListener('change', function() {
                if (this.value) {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
            
            // Initialiser l'état de l'animation
            if (select.value) {
                select.classList.add('has-value');
            }
        }
    });
});

// Script pour faire défiler automatiquement vers la section en cas de pagination
document.addEventListener('DOMContentLoaded', function() {
    // Vérifie si l'URL contient un fragment d'ancrage (hash)
    if (window.location.hash) {
        // Petit délai pour assurer que tous les éléments sont chargés
        setTimeout(function() {
            // Récupère l'élément correspondant à l'ancre
            const targetElement = document.querySelector(window.location.hash);
            if (targetElement) {
                // Fait défiler vers l'élément avec un léger décalage pour éviter que l'en-tête ne le recouvre
                window.scrollTo({
                    top: targetElement.offsetTop - 100, // 100px de marge pour éviter la navigation
                    behavior: 'smooth'
                });
            }
        }, 300);
    }
}); 