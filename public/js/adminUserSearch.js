document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('terminalSearch');
    const tableBody = document.querySelector('#userTableBody') || document.querySelector('.userTableBody');
    const rows = document.querySelectorAll('.user-row');

    if (!searchInput || !tableBody) {
        console.error("Matrix System Error: Terminal components missing.");
        return;
    }

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        let matchCount = 0;

        tableBody.style.transition = 'opacity 0.15s ease';
        tableBody.style.opacity = '0.3';

        setTimeout(() => {
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                    row.style.animation = "matrixFadeIn 0.3s ease forwards";
                    matchCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            tableBody.style.opacity = '1';
            handleNoResults(matchCount, tableBody, query);
        }, 150);
    });
});

function handleNoResults(count, container, query) {
    let emptyMsg = document.getElementById('matrix-empty-msg');
    
    if (count === 0 && query !== "") {
        if (!emptyMsg) {
            emptyMsg = document.createElement('tr');
            emptyMsg.id = 'matrix-empty-msg';
            emptyMsg.innerHTML = `
                <td colspan="5" class="text-center py-5" style="color: #ff003c;">
                    [!] ERROR: NO_RECORDS_MATCHING_CRITERIA<br>
                    <span style="font-size: 0.8rem; opacity: 0.7;">SIGNAL_LOST...</span>
                </td>`;
            container.appendChild(emptyMsg);
        }
    } else if (emptyMsg) {
        emptyMsg.remove();
    }
}