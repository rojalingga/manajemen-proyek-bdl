<?php include __DIR__ . '/../layout/header.php'; ?>

<!-- Search & Filter Style -->
<style>
    .rd-megamenu-title {
        font-size: 15px;
    }
    
    /* SEARCH BAR */
    .search-container {
        position: relative;
        margin-left: 0;
        min-width: 200px;
    }

    .search-input {
        padding: 8px 35px 8px 12px;
        border: 1px solid #ddd;
        border-radius: 20px;
        width: 200px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #2a93e0;
        width: 250px;
    }

    .search-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
    }

    /* Filter Bar Title */
    .filter-title {
        margin-bottom: 20px;
    }

    /* Tinggi Elemen Filter */
    .filter-bar .form-control,
    .filter-bar button {
        height: 40px;
    }

    /* BARIS FILTER — Sejajar Semua */
    .filter-bar {
        margin-top: 15px;
        margin-bottom: 15px;
        display: flex !important;
        flex-wrap: nowrap !important;
        gap: 12px;
        align-items: center;
    }

    /* Min width supaya tidak turun */
    .filter-bar select {
        min-width: 150px;
    }

    /* RESET BUTTON — warna hijau */
    #filter-reset {
        background: #00b8a9;
        color: #fff;
        border: none;
        padding: 0 20px;
        height: 40px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: 0.2s;
    }

    #filter-reset:hover {
        background: #009d91;
    }
</style>

<!-- FILTER BAR -->
<section class="section section-sm">
    <div class="container">

        <h3 class="mt-5 filter-title">Proyek Digital</h3>

        <div class="filter-bar">

            <!-- Search -->
            <div class="search-container">
                <input id="gallery-search" type="search" class="search-input" placeholder="Cari proyek...">
                <button class="search-btn"><i class="fa fa-search"></i></button>
            </div>

            <!-- Category -->
            <select id="filter-category" class="form-control">
                <option value="">Kategori</option>
                <option>UI/UX</option>
                <option>Game</option>
                <option>AR/VR</option>
                <option>Mobile App</option>
            </select>

            <!-- Technology -->
            <select id="filter-tech" class="form-control">
                <option value="">Teknologi</option>
                <option>Web</option>
                <option>Unity</option>
                <option>Unreal</option>
                <option>Flutter</option>
            </select>

            <!-- Year -->
            <select id="filter-year" class="form-control">
                <option value="">Tahun</option>
                <option>2025</option>
                <option>2024</option>
                <option>2023</option>
                <option>2022</option>
            </select>

            <!-- Sort -->
            <select id="sort-by" class="form-control">
                <option value="newest">Terbaru</option>
                <option value="popular">Terpopuler</option>
                <option value="az">A - Z</option>
            </select>

            <!-- Reset -->
            <button id="filter-reset">Reset</button>

        </div>
    </div>
</section>
<section class="section section-xl bg-default">
    <div class="container">
        <div class="row row-40 justify-content-center">

            <!-- CARD 1 -->
            <div class="col-sm-12 col-md-12 wow fadeInRight">
                <article class="product-big"
                    data-category="UI/UX"
                    data-tech="Web"
                    data-year="2024"
                    data-views="120"
                    data-title="Spain, Benidorm">

                    <div class="unit flex-column flex-md-row align-items-md-stretch">
                        <div class="unit-left">
                            <a class="product-big-figure" href="proyek-digital/detail-proyek">
                                <img src="images/product-big-1-600x366.jpg" alt="">
                            </a>
                        </div>
                        <div class="unit-body">
                            <div class="product-big-body">
                                <h5 class="product-big-title">
                                    <a href="/proyek-digital/detail-proyek">Spain, Benidorm</a>
                                </h5>
                                <div class="group-sm group-middle justify-content-start">
                                    <div class="product-big-rating">
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star_half"></span>
                                    </div>
                                </div>
                                <p class="product-big-text">Amet nisl purus in mollis nunc sed id…</p>
                                <a class="button button-black-outline button-ujarak" href="/proyek-digital/detail-proyek">Lihat Detail</a>
                                <div class="product-big-price-wrap">$790</div>
                            </div>
                        </div>
                    </div>

                </article>
            </div>

            <!-- CARD 2 -->
            <div class="col-sm-12 col-md-12 wow fadeInLeft">
                <article class="product-big"
                    data-category="Game"
                    data-tech="Unity"
                    data-year="2023"
                    data-views="350"
                    data-title="Mauritius Island, Africa">

                    <div class="unit flex-column flex-md-row align-items-md-stretch">
                        <div class="unit-left">
                            <a class="product-big-figure" href="single-tour.html">
                                <img src="images/product-big-2-600x366.jpg" alt="">
                            </a>
                        </div>
                        <div class="unit-body">
                            <div class="product-big-body">
                                <h5 class="product-big-title">
                                    <a href="single-tour.html">Mauritius Island, Africa</a>
                                </h5>
                                <div class="group-sm group-middle justify-content-start">
                                    <div class="product-big-rating">
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star_half"></span>
                                    </div>
                                </div>
                                <p class="product-big-text">Lacus luctus accumsan tortor posuere…</p>
                                <a class="button button-black-outline button-ujarak" href="/proyek-digital/{id}">Lihat Detail</a>
                                <div class="product-big-price-wrap">$790</div>
                            </div>
                        </div>
                    </div>

                </article>
            </div>

            <!-- CARD 3 -->
            <div class="col-sm-12 col-md-12 wow fadeInLeft">
                <article class="product-big"
                    data-category="AR/VR"
                    data-tech="Unity"
                    data-year="2025"
                    data-views="900"
                    data-title="Julian Alps">

                    <div class="unit flex-column flex-md-row align-items-md-stretch">
                        <div class="unit-left">
                            <a class="product-big-figure" href="single-tour.html">
                                <img src="images/product-big-3-600x366.jpg" alt="">
                            </a>
                        </div>
                        <div class="unit-body">
                            <div class="product-big-body">
                                <h5 class="product-big-title">
                                    <a href="single-tour.html">Julian Alps</a>
                                </h5>
                                <div class="group-sm group-middle justify-content-start">
                                    <div class="product-big-rating">
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star_half"></span>
                                    </div>
                                </div>
                                <p class="product-big-text">Accumsan in nisl nisi scelerisque…</p>
                                <a class="button button-black-outline button-ujarak" href="/proyek-digital/{id}">Lihat Detail</a>
                                <div class="product-big-price-wrap">$2300</div>
                            </div>
                        </div>
                    </div>

                </article>
            </div>

            <!-- CARD 4 -->
            <div class="col-sm-12 col-md-12 wow fadeInRight">
                <article class="product-big"
                    data-category="Mobile App"
                    data-tech="Flutter"
                    data-year="2022"
                    data-views="240"
                    data-title="Mediterranean Coast, France">

                    <div class="unit flex-column flex-md-row align-items-md-stretch">
                        <div class="unit-left">
                            <a class="product-big-figure" href="single-tour.html">
                                <img src="images/product-big-4-600x366.jpg" alt="">
                            </a>
                        </div>
                        <div class="unit-body">
                            <div class="product-big-body">
                                <h5 class="product-big-title">
                                    <a href="single-tour.html">Mediterranean Coast, France</a>
                                </h5>
                                <div class="group-sm group-middle justify-content-start">
                                    <div class="product-big-rating">
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star"></span>
                                        <span class="icon material-icons-star_half"></span>
                                    </div>
                                </div>
                                <p class="product-big-text">Amet massa vitae tortor condimentum…</p>
                                <a class="button button-black-outline button-ujarak" href="/proyek-digital/{id}">Lihat Detail</a>
                                <div class="product-big-price-wrap">$1800</div>
                            </div>
                        </div>
                    </div>

                </article>
            </div>

        </div>

        <!-- PAGINATION -->
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item page-item-control disabled">
                            <a class="page-item page-item-control disabled" class="page-link" href="#" aria-label="Previous"><span class="icon" aria-hidden="true"></span></a>
                        </li>
                        <li class="page-item active"><span class="page-link">1</span></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item page-item-control">
                            <a class="page-item page-item-control" class="page-link" href="#" aria-label="Next"><span class="icon" aria-hidden="true"></span></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

    </div>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>


<!-- SCRIPT FILTER -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.row-sm.row-40');
    const articles = Array.from(container.querySelectorAll('article.product-big'));

    const qSearch = document.getElementById('gallery-search');
    const selCategory = document.getElementById('filter-category');
    const selTech = document.getElementById('filter-tech');
    const selYear = document.getElementById('filter-year');
    const selSort = document.getElementById('sort-by');
    const btnReset = document.getElementById('filter-reset');

    function normalize(s) {
        return (s || '').toLowerCase().trim();
    }

    function matches(a, q, c, t, y) {
        const title = normalize(a.dataset.title);
        const summary = normalize(a.querySelector('.product-big-text')?.innerText);

        if (q && !title.includes(q) && !summary.includes(q)) return false;
        if (c && normalize(a.dataset.category) !== c) return false;
        if (t && normalize(a.dataset.tech) !== t) return false;
        if (y && normalize(a.dataset.year) !== y) return false;

        return true;
    }

    function applyFilterSort() {
        const q = normalize(qSearch.value);
        const c = normalize(selCategory.value);
        const t = normalize(selTech.value);
        const y = normalize(selYear.value);

        const sortBy = selSort.value;

        let visible = articles.filter(a => matches(a, q, c, t, y));

        visible.sort((a, b) => {
            if (sortBy === 'az') return normalize(a.dataset.title).localeCompare(normalize(b.dataset.title));
            if (sortBy === 'popular') return (+b.dataset.views) - (+a.dataset.views);
            return (+b.dataset.year) - (+a.dataset.year); // newest
        });

        container.innerHTML = "";

        if (visible.length === 0) {
            container.innerHTML = "<p>Tidak ada proyek ditemukan.</p>";
            return;
        }

        visible.forEach(a => container.appendChild(a));
    }

    qSearch.oninput =
    selCategory.onchange =
    selTech.onchange =
    selYear.onchange =
    selSort.onchange = applyFilterSort;

    btnReset.onclick = () => {
        qSearch.value = "";
        selCategory.value = "";
        selTech.value = "";
        selYear.value = "";
        selSort.value = "newest";
        applyFilterSort();
    };

    applyFilterSort();
});
</script>

