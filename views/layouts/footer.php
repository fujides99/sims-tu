</div> 
    </div> 

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.datatable').DataTable();
        });

        var toggleButton = document.getElementById("menu-toggle");
        var wrapper = document.getElementById("wrapper");

        toggleButton.onclick = function () {
            wrapper.classList.toggle("toggled");
        };
    </script>
</body>
</html>