<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Modules</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .course-tile {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        .course-tile:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .chapter-list {
            margin-top: 15px;
        }
        .search-bar {
            margin-bottom: 30px;
        }
        .filter-section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Learning Modules</h1>
        
        <!-- Search and Filter Section -->
        <div class="row">
            <div class="col-md-8">
                <div class="search-bar">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search modules...">
                </div>
            </div>
            <div class="col-md-4">
                <div class="filter-section">
                    <select class="form-select" id="moduleType">
                        <option value="all">All Types</option>
                        <option value="traditional">Traditional</option>
                        <option value="interactive">Interactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Add New Module Button -->
        <div class="mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                <i class="fas fa-plus"></i> Add New Module
            </button>
        </div>

        <!-- Course Tiles Section -->
        <div class="row" id="courseTiles">
            <!-- Course tiles will be dynamically loaded here -->
        </div>
    </div>

    <!-- Add Module Modal -->
    <div class="modal fade" id="addModuleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Learning Module</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addModuleForm">
                        <div class="mb-3">
                            <label class="form-label">Module Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Module Type</label>
                            <select class="form-select" name="type" required>
                                <option value="traditional">Traditional</option>
                                <option value="interactive">Interactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveModule">Save Module</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to load course tiles
        function loadCourseTiles() {
            // This will be replaced with actual API call
            const sampleData = [
                {
                    id: 1,
                    title: "Introduction to Programming",
                    type: "traditional",
                    description: "Learn the basics of programming",
                    chapters: [
                        { id: 1, title: "Getting Started", type: "traditional" },
                        { id: 2, title: "Variables and Data Types", type: "interactive" }
                    ]
                },
                // Add more sample data as needed
            ];

            const courseTilesContainer = document.getElementById('courseTiles');
            courseTilesContainer.innerHTML = '';

            sampleData.forEach(course => {
                const courseTile = createCourseTile(course);
                courseTilesContainer.appendChild(courseTile);
            });
        }

        // Function to create a course tile
        function createCourseTile(course) {
            const tile = document.createElement('div');
            tile.className = 'col-md-6 col-lg-4';
            tile.innerHTML = `
                <div class="course-tile">
                    <h3>${course.title}</h3>
                    <p>${course.description}</p>
                    <div class="chapter-list">
                        <h4>Chapters</h4>
                        <ul class="list-group">
                            ${course.chapters.map(chapter => `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    ${chapter.title}
                                    <span class="badge bg-${chapter.type === 'interactive' ? 'primary' : 'secondary'}">
                                        ${chapter.type}
                                    </span>
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-sm btn-primary" onclick="editModule(${course.id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteModule(${course.id})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            `;
            return tile;
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', () => {
            loadCourseTiles();
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            // Implement search logic here
        });

        // Filter functionality
        document.getElementById('moduleType').addEventListener('change', (e) => {
            const filterType = e.target.value;
            // Implement filter logic here
        });

        // Save new module
        document.getElementById('saveModule').addEventListener('click', () => {
            // Implement save logic here
            const modal = bootstrap.Modal.getInstance(document.getElementById('addModuleModal'));
            modal.hide();
        });
    </script>
</body>
</html> 