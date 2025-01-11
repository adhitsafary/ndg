<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventori Tables</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Inventori Tables</h1>
        <form action="/tables/create" method="POST" id="createTableForm">
            @csrf
            <div class="mb-3">
                <label for="table_name" class="form-label">Table Name</label>
                <input type="text" class="form-control" id="table_name" name="table_name" required>
            </div>
            <div id="columns">
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" class="form-control" name="columns[0][name]" placeholder="Column Name"
                            required>
                    </div>
                    <div class="col">
                        <select class="form-control" name="columns[0][type]" required>
                            <option value="string">String</option>
                            <option value="integer">Integer</option>
                            <option value="decimal">Decimal</option>
                            <option value="date">Date</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="button" id="addColumn" class="btn btn-secondary">Add Column</button>
            <button type="submit" class="btn btn-primary">Create Table</button>
        </form>
    </div>
    <script>
        let columnIndex = 1;
        document.getElementById('addColumn').addEventListener('click', function() {
            const columns = document.getElementById('columns');
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'mb-3');
            newRow.innerHTML = `
            <div class="col">
                <input type="text" class="form-control" name="columns[${columnIndex}][name]" placeholder="Column Name" required>
            </div>
            <div class="col">
                <select class="form-control" name="columns[${columnIndex}][type]" required>
                    <option value="string">String</option>
                    <option value="integer">Integer</option>
                    <option value="decimal">Decimal</option>
                    <option value="date">Date</option>
                </select>
            </div>
        `;
            columns.appendChild(newRow);
            columnIndex++;
        });
    </script>
</body>

</html>
