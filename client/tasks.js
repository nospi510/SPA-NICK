// tasks.js

document.addEventListener('DOMContentLoaded', function() {
    const statusForms = document.querySelectorAll('.status-form');

    // Ajouter un écouteur d'événement sur chaque formulaire de statut
    statusForms.forEach(function(form) {
        const select = form.querySelector('select');
        const statusCell = form.parentNode;
        const initialStatus = statusCell.dataset.status;

        // Mettre à jour la couleur du champ "Statut" lors du chargement de la page
        updateStatusColor(statusCell, initialStatus);

        select.addEventListener('change', function() {
            const newStatus = select.value;

            // Mettre à jour la couleur du champ "Statut" lorsqu'une nouvelle option est sélectionnée
            updateStatusColor(statusCell, newStatus);

            // Mettre à jour le statut dans la base de données en utilisant htmx
            const taskId = form.dataset.taskId;
            hx.patch('/update_status.php', { task_id: taskId, newStatus: newStatus });
        });
    });

    function updateStatusColor(statusCell, newStatus) {
        if (newStatus === 'en attente') {
            statusCell.style.backgroundColor = 'orange';
        } else if (newStatus === 'en cours') {
            statusCell.style.backgroundColor = 'green';
        } else if (newStatus === 'terminée') {
            statusCell.style.backgroundColor = 'red';
        }
    }

    // Ajouter un écouteur d'événement sur chaque bouton de suppression
    const deleteButtons = document.querySelectorAll('.delete-button');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const taskId = button.dataset.taskId;

            // Supprimer la tâche de la base de données en utilisant htmx
            hx.delete('/delete_task.php', { task_id: taskId });
        });
    });
});
