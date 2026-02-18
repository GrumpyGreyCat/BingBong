document.addEventListener('DOMContentLoaded', function() {
    // 1. Grab elements
    const searchInput = document.getElementById('terminalSearch');
    const tableBody = document.getElementById('userTableBody');
    const sortBtn = document.getElementById('sortUsername');

    // Verification check
    if (!searchInput || !tableBody) {
        console.warn("Matrix Debug: Missing DOM elements. Search or Table Body not found.");
        return;
    }

    // Capture initial rows
    let rows = Array.from(tableBody.querySelectorAll('.user-row'));
    let ascending = true;

    // --- SEARCH LOGIC ---
    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        let matchCount = 0;

        // Visual feedback: Dim the table while "processing"
        tableBody.style.opacity = '0.5';

        // Re-query rows in case they were re-sorted
        const currentRows = tableBody.querySelectorAll('.user-row');

        currentRows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(query)) {
                row.style.display = ''; // Show
                matchCount++;
            } else {
                row.style.display = 'none'; // Hide
            }
        });

        tableBody.style.opacity = '1';
        handleNoResults(matchCount, tableBody, query);
    });

    // --- SORT LOGIC ---
    if (sortBtn) {
        sortBtn.addEventListener('click', function() {
            const rowsToSort = Array.from(tableBody.querySelectorAll('.user-row'));
            const label = sortBtn.querySelector('span');

            rowsToSort.sort((a, b) => {
                const valA = a.querySelector('.user-name').textContent.trim().toLowerCase();
                const valB = b.querySelector('.user-name').textContent.trim().toLowerCase();
                return ascending ? valA.localeCompare(valB) : valB.localeCompare(valA);
            });

            ascending = !ascending;
            if (label) label.textContent = ascending ? '[SORT_ASC]' : '[SORT_DESC]';

            // Re-inject sorted rows
            rowsToSort.forEach(row => tableBody.appendChild(row));
        });
    }
});

function handleNoResults(count, container, query) {
    let emptyMsg = document.getElementById('matrix-empty-msg');
    if (count === 0 && query !== "") {
        if (!emptyMsg) {
            const tr = document.createElement('tr');
            tr.id = 'matrix-empty-msg';
            tr.innerHTML = `<td colspan="5" class="text-center py-5 terminal-error-text">[!] NO_MATCHING_RECORDS_FOUND</td>`;
            container.appendChild(tr);
        }
    } else if (emptyMsg) {
        emptyMsg.remove();
    }
}