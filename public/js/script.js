// Gestionnaire pour le chargement dynamique des marques et modèles
document.addEventListener('DOMContentLoaded', function() {
    // Sélecteurs pour les champs de formulaire
    const typeSelect = document.getElementById('type');
    const brandSelect = document.getElementById('brand');
    const seriesSelect = document.getElementById('series');
    
    if (typeSelect && brandSelect && seriesSelect) {
        // Les marques disponibles par type de véhicule
        const vehicleBrands = {
            '1': ['Audi', 'BMW', 'Mercedes', 'Toyota', 'Honda', 'Ford', 'Chevrolet', 'Volkswagen', 'Nissan', 'Hyundai'],
            '2': ['Harley-Davidson', 'Honda', 'Yamaha', 'Kawasaki', 'Suzuki', 'Ducati', 'BMW', 'KTM', 'Triumph', 'Royal Enfield'],
            '3': ['Volvo', 'Scania', 'MAN', 'Mercedes-Benz', 'DAF', 'Iveco', 'Renault Trucks', 'Kenworth', 'Peterbilt', 'Freightliner'],
            '4': ['Mercedes-Benz', 'Ford', 'Volkswagen', 'Renault', 'Fiat', 'Peugeot', 'Citroën', 'Iveco', 'Nissan', 'Toyota'],
            '5': ['Jeep', 'Land Rover', 'BMW', 'Mercedes-Benz', 'Audi', 'Toyota', 'Nissan', 'Kia', 'Hyundai', 'Ford']
        };
        
        // Les séries disponibles par marque
        const brandSeries = {
            'Audi': ['A1', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Q3', 'Q5', 'Q7', 'Q8', 'e-tron'],
            'BMW': ['Série 1', 'Série 2', 'Série 3', 'Série 4', 'Série 5', 'Série 6', 'Série 7', 'Série 8', 'X1', 'X3', 'X5', 'X6', 'X7'],
            'Mercedes': ['Classe A', 'Classe B', 'Classe C', 'Classe E', 'Classe S', 'GLA', 'GLC', 'GLE', 'GLS'],
            'Toyota': ['Yaris', 'Corolla', 'Camry', 'Prius', 'C-HR', 'RAV4', 'Highlander', 'Land Cruiser'],
            'Honda': ['Civic', 'Accord', 'CR-V', 'HR-V', 'Pilot', 'Odyssey'],
            'Harley-Davidson': ['Sportster', 'Softail', 'Touring', 'Street', 'LiveWire'],
            'Yamaha': ['YZF-R1', 'YZF-R6', 'MT-07', 'MT-09', 'Tracer', 'TMAX'],
            'Volvo': ['FH', 'FM', 'FMX', 'FE', 'FL'],
            'Scania': ['R-series', 'S-series', 'G-series', 'P-series'],
            'Mercedes-Benz': ['Sprinter', 'Vito', 'Classe V', 'Citan', 'eSprinter', 'Actros', 'Arocs', 'Atego'],
            'Ford': ['Transit', 'Transit Connect', 'Transit Custom', 'Ranger', 'F-150', 'Explorer', 'Edge', 'Kuga', 'Puma'],
            'Jeep': ['Renegade', 'Compass', 'Cherokee', 'Grand Cherokee', 'Wrangler'],
            'Land Rover': ['Range Rover Evoque', 'Range Rover Sport', 'Range Rover Velar', 'Discovery', 'Discovery Sport']
        };
        
        // Fonction pour charger les marques en fonction du type de véhicule
        function loadBrands() {
            // Réinitialiser les listes
            brandSelect.innerHTML = '<option value="" selected disabled>Sélectionner une marque</option>';
            seriesSelect.innerHTML = '<option value="" selected disabled>Sélectionner une série</option>';
            
            seriesSelect.disabled = true;
            
            if (!typeSelect.value) {
                brandSelect.disabled = true;
                return;
            }
            
            const brands = vehicleBrands[typeSelect.value];
            if (brands && brands.length > 0) {
                brandSelect.disabled = false;
                brands.forEach(brand => {
                    const option = document.createElement('option');
                    option.value = brand;
                    option.textContent = brand;
                    brandSelect.appendChild(option);
                });
            } else {
                brandSelect.disabled = true;
            }
        }
        
        // Fonction pour charger les séries en fonction de la marque
        function loadSeries() {
            seriesSelect.innerHTML = '<option value="" selected disabled>Sélectionner une série</option>';
            
            if (!brandSelect.value) {
                seriesSelect.disabled = true;
                return;
            }
            
            const series = brandSeries[brandSelect.value];
            if (series && series.length > 0) {
                seriesSelect.disabled = false;
                series.forEach(seriesName => {
                    const option = document.createElement('option');
                    option.value = seriesName;
                    option.textContent = seriesName;
                    seriesSelect.appendChild(option);
                });
            } else {
                // Aucune série trouvée
                const noSeriesOption = document.createElement('option');
                noSeriesOption.textContent = "Aucune série disponible";
                noSeriesOption.disabled = true;
                seriesSelect.appendChild(noSeriesOption);
                seriesSelect.disabled = true;
            }
        }
        
        // Écouteurs d'événements
        typeSelect.addEventListener('change', loadBrands);
        brandSelect.addEventListener('change', loadSeries);
        
        // Initialisation - désactiver la marque et la série par défaut
        brandSelect.disabled = true;
        seriesSelect.disabled = true;
    }
});

// Animation des compteurs
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter');
    
    if(counters.length) {
        const observerOptions = {
            threshold: 0.5
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    const counter = entry.target.querySelector('h3');
                    const target = parseInt(counter.getAttribute('data-count'));
                    let count = 0;
                    const duration = 2000; // durée en millisecondes
                    const step = Math.ceil(target / (duration / 20));
                    
                    const updateCounter = () => {
                        count += step;
                        if(count < target) {
                            counter.textContent = count;
                            setTimeout(updateCounter, 20);
                        } else {
                            counter.textContent = target;
                        }
                    };
                    
                    updateCounter();
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        counters.forEach(counter => {
            observer.observe(counter);
        });
    }
});

// Gestionnaire pour les filtres rapides
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    if (filterButtons.length) {
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filterType = this.getAttribute('data-filter');
                const filterValue = this.getAttribute('data-value');
                const targetSelect = document.getElementById(filterType);
                
                if (targetSelect) {
                    // Chercher l'option avec la valeur correspondante
                    const options = targetSelect.querySelectorAll('option');
                    
                    options.forEach(option => {
                        if (option.value === filterValue) {
                            option.selected = true;
                            
                            // Activer ce bouton et désactiver les autres du même type
                            filterButtons.forEach(btn => {
                                if (btn.getAttribute('data-filter') === filterType) {
                                    btn.classList.toggle('active', btn === this);
                                }
                            });
                            
                            // Déclencher l'événement change pour activer les dépendances
                            const event = new Event('change');
                            targetSelect.dispatchEvent(event);
                        }
                    });
                }
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Fonctionnalité existante pour les formulaires et autres
    
    // Gestion des filtres rapides
    const quickFilterButtons = document.querySelectorAll('.quick-filter-btn');
    if (quickFilterButtons.length > 0) {
        quickFilterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Retirer la classe active de tous les boutons
                quickFilterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Ajouter la classe active au bouton cliqué
                this.classList.add('active');
                
                const type = this.getAttribute('data-type');
                
                // Sélectionner automatiquement le type correspondant dans le formulaire
                const typeSelect = document.getElementById('type');
                if (typeSelect) {
                    switch(type) {
                        case 'car':
                            typeSelect.value = '1';
                            break;
                        case 'moto':
                            typeSelect.value = '2';
                            break;
                        case 'truck':
                            typeSelect.value = '3';
                            break;
                    }
                    
                    // Déclencher l'événement change pour mettre à jour les autres champs dépendants
                    typeSelect.dispatchEvent(new Event('change'));
                }
            });
        });
    }
    
    // Animation des icônes flottantes
    function animateFloatingIcons() {
        const floatingIcons = document.querySelectorAll('.floating-icon');
        if (floatingIcons.length > 0) {
            floatingIcons.forEach(icon => {
                // Appliquer un léger décalage aléatoire à l'animation
                const randomDelay = Math.random() * 5;
                icon.style.animationDelay = `${randomDelay}s`;
            });
        }
    }
    
    animateFloatingIcons();
}); 