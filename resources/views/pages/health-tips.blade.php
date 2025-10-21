@extends('layouts.app')

@section('title', 'Health Tips - ApexCare')

@section('content')
<style>
/* Health Tips Page Specific Styles */
.btn-check:checked + .btn-outline-primary {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

.tip-card {
    transition: all 0.3s ease;
}

.tip-card:hover {
    transform: translateY(-5px);
}

.category-filter {
    gap: 0.5rem;
}

#no-results-message, #no-search-results {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.card-img-top {
    transition: transform 0.3s ease;
}

.tip-card:hover .card-img-top {
    transform: scale(1.05);
}

.search-highlight {
    background-color: yellow;
    padding: 2px 4px;
    border-radius: 3px;
}
</style>
<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">Health Tips & Wellness</h1>
        <p class="lead text-muted">Expert advice for a healthier, happier life</p>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="btn-group category-filter" role="group" aria-label="Category filter">
                        <input type="radio" class="btn-check" name="category" id="all" autocomplete="off" checked>
                        <label class="btn btn-outline-primary" for="all">All Tips</label>

                        <input type="radio" class="btn-check" name="category" id="general" autocomplete="off">
                        <label class="btn btn-outline-primary" for="general">General Health</label>

                        <input type="radio" class="btn-check" name="category" id="fitness" autocomplete="off">
                        <label class="btn btn-outline-primary" for="fitness">Fitness</label>

                        <input type="radio" class="btn-check" name="category" id="nutrition" autocomplete="off">
                        <label class="btn btn-outline-primary" for="nutrition">Nutrition</label>

                        <input type="radio" class="btn-check" name="category" id="mental" autocomplete="off">
                        <label class="btn btn-outline-primary" for="mental">Mental Health</label>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchTips" class="form-control" placeholder="Search tips...">
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <small class="text-muted" id="results-counter">
                        Showing {{ count($healthTips) }} health tips
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Health Tips Grid -->
    <div class="row" id="tipsContainer">
        @foreach($healthTips as $tip)
            <div class="col-lg-4 col-md-6 mb-4 tip-card" data-category="{{ strtolower(str_replace(' ', '', $tip['category'])) }}">
                <div class="card h-100 shadow-sm">
                    <img src="{{ $tip['image'] }}" class="card-img-top" alt="{{ $tip['title'] }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary">{{ $tip['category'] }}</span>
                            <small class="text-muted">{{ $tip['created_at']->diffForHumans() }}</small>
                        </div>
                        <h5 class="card-title">{{ $tip['title'] }}</h5>
                        <p class="card-text text-muted">{{ $tip['content'] }}</p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-heart text-danger me-1"></i>Helpful tip
                            </small>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="shareTip('{{ $tip['title'] }}')">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                                <button class="btn btn-outline-success btn-sm" onclick="likeTip(this)">
                                    <i class="fas fa-thumbs-up"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Newsletter Subscription -->
    <div class="bg-primary text-white rounded-3 p-4 mt-5">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="fw-bold mb-2">Stay Updated with Health Tips</h4>
                <p class="mb-0">Subscribe to our newsletter and get weekly health tips delivered to your inbox.</p>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <form class="d-flex">
                    <input type="email" class="form-control me-2" placeholder="Enter your email" required>
                    <button type="submit" class="btn btn-light">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Health Facts -->
    <div class="mt-5">
        <h3 class="text-center mb-4">Quick Health Facts</h3>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center p-4 bg-light rounded-3">
                    <div class="display-4 text-primary mb-2"><img src='..‍/resourcesimages/steps.jpg' width='50px'></div>
                    <h6>Daily Steps</h6>
                    <p class="small text-muted mb-0">Aim for 10,000 steps per day for optimal health</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center p-4 bg-light rounded-3">
                    <div class="display-4 text-primary mb-2">💧</div>
                    <h6>Water Intake</h6>
                    <p class="small text-muted mb-0">Drink 8-10 glasses of water daily</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center p-4 bg-light rounded-3">
                    <div class="display-4 text-primary mb-2">😴</div>
                    <h6>Sleep Hours</h6>
                    <p class="small text-muted mb-0">7-9 hours of quality sleep each night</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center p-4 bg-light rounded-3">
                    <div class="display-4 text-primary mb-2">🥗</div>
                    <h6>Fruits & Veggies</h6>
                    <p class="small text-muted mb-0">5 servings of fruits and vegetables daily</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category filtering
    const categoryButtons = document.querySelectorAll('input[name="category"]');
    const tipCards = document.querySelectorAll('.tip-card');
    const tipsContainer = document.getElementById('tipsContainer');
    
    // Function to show/hide cards based on category
    function filterByCategory(selectedCategory) {
        let visibleCount = 0;
        
        tipCards.forEach(card => {
            const cardCategory = card.dataset.category;
            
            if (selectedCategory === 'all') {
                card.style.display = 'block';
                visibleCount++;
            } else {
                // More precise category matching
                const categoryMap = {
                    'general': ['generalhealth'],
                    'fitness': ['fitness'],
                    'nutrition': ['nutrition'],
                    'mental': ['mentalhealth']
                };
                
                if (categoryMap[selectedCategory] && categoryMap[selectedCategory].includes(cardCategory)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            }
        });
        
        // Update results counter
        updateResultsCounter(visibleCount, selectedCategory);
        
        // Show message if no tips found for selected category
        showNoResultsMessage(visibleCount, selectedCategory);
    }
    
    // Function to update results counter
    function updateResultsCounter(count, category = 'all') {
        const counter = document.getElementById('results-counter');
        const categoryNames = {
            'all': 'health tips',
            'general': 'general health tips',
            'fitness': 'fitness tips',
            'nutrition': 'nutrition tips',
            'mental': 'mental health tips',
            'search': 'search results'
        };
        
        const categoryName = categoryNames[category] || 'health tips';
        counter.textContent = `Showing ${count} ${categoryName}`;
    }
    
    // Function to show no results message
    function showNoResultsMessage(count, category) {
        let existingMessage = document.getElementById('no-results-message');
        
        if (count === 0) {
            if (!existingMessage) {
                const message = document.createElement('div');
                message.id = 'no-results-message';
                message.className = 'col-12 text-center py-5';
                message.innerHTML = `
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>No tips found</h4>
                    <p class="text-muted">No health tips available for the selected category.</p>
                    <button class="btn btn-primary" onclick="resetFilters()">Show All Tips</button>
                `;
                tipsContainer.appendChild(message);
            }
        } else {
            if (existingMessage) {
                existingMessage.remove();
            }
        }
    }
    
    // Category filter event listeners
    categoryButtons.forEach(button => {
        button.addEventListener('change', function() {
            const selectedCategory = this.id;
            filterByCategory(selectedCategory);
            
            // Update active state styling
            categoryButtons.forEach(btn => {
                const label = document.querySelector(`label[for="${btn.id}"]`);
                if (btn.checked) {
                    label.classList.remove('btn-outline-primary');
                    label.classList.add('btn-primary');
                } else {
                    label.classList.remove('btn-primary');
                    label.classList.add('btn-outline-primary');
                }
            });
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchTips');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        let visibleCount = 0;
        
        tipCards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            const content = card.querySelector('.card-text').textContent.toLowerCase();
            const category = card.querySelector('.badge').textContent.toLowerCase();
            
            if (title.includes(searchTerm) || content.includes(searchTerm) || category.includes(searchTerm)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Update results counter for search
        if (searchTerm.length > 0) {
            updateResultsCounter(visibleCount, 'search');
        } else {
            // Reset to current category filter
            const activeCategory = document.querySelector('input[name="category"]:checked').id;
            filterByCategory(activeCategory);
        }
        
        // Show message if no search results found
        showSearchMessage(visibleCount, searchTerm);
    });
    
    // Function to show search no results message
    function showSearchMessage(count, searchTerm) {
        let existingMessage = document.getElementById('no-search-results');
        
        if (count === 0 && searchTerm.length > 0) {
            if (!existingMessage) {
                const message = document.createElement('div');
                message.id = 'no-search-results';
                message.className = 'col-12 text-center py-5';
                message.innerHTML = `
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>No results found</h4>
                    <p class="text-muted">No health tips match your search for "${searchTerm}".</p>
                    <button class="btn btn-primary" onclick="clearSearch()">Clear Search</button>
                `;
                tipsContainer.appendChild(message);
            }
        } else {
            if (existingMessage) {
                existingMessage.remove();
            }
        }
    }
});

// Global functions
function resetFilters() {
    document.getElementById('all').checked = true;
    document.getElementById('searchTips').value = '';
    
    // Show all cards
    document.querySelectorAll('.tip-card').forEach(card => {
        card.style.display = 'block';
    });
    
    // Remove any no results messages
    const noResults = document.getElementById('no-results-message');
    if (noResults) noResults.remove();
    
    const noSearch = document.getElementById('no-search-results');
    if (noSearch) noSearch.remove();
    
    // Reset button styling
    document.querySelectorAll('input[name="category"]').forEach(btn => {
        const label = document.querySelector(`label[for="${btn.id}"]`);
        if (btn.id === 'all') {
            label.classList.remove('btn-outline-primary');
            label.classList.add('btn-primary');
        } else {
            label.classList.remove('btn-primary');
            label.classList.add('btn-outline-primary');
        }
    });
}

function clearSearch() {
    document.getElementById('searchTips').value = '';
    
    // Trigger category filter to show current category
    const activeCategory = document.querySelector('input[name="category"]:checked').id;
    document.querySelectorAll('.tip-card').forEach(card => {
        if (activeCategory === 'all') {
            card.style.display = 'block';
        } else {
            const cardCategory = card.dataset.category;
            const categoryMap = {
                'general': ['generalhealth'],
                'fitness': ['fitness'],
                'nutrition': ['nutrition'],
                'mental': ['mentalhealth']
            };
            
            if (categoryMap[activeCategory] && categoryMap[activeCategory].includes(cardCategory)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }
    });
    
    // Remove search no results message
    const noSearch = document.getElementById('no-search-results');
    if (noSearch) noSearch.remove();
}

function shareTip(title) {
    if (navigator.share) {
        navigator.share({
            title: 'Health Tip: ' + title,
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href);
        alert('Link copied to clipboard!');
    }
}

function likeTip(button) {
    const icon = button.querySelector('i');
    if (icon.classList.contains('fas')) {
        icon.classList.remove('fas');
        icon.classList.add('far');
        button.classList.remove('btn-outline-success');
        button.classList.add('btn-outline-secondary');
    } else {
        icon.classList.remove('far');
        icon.classList.add('fas');
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-outline-success');
    }
}
</script>
@endsection