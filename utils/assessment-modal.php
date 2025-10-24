<!-- Assessment Modal -->
<div id="assessmentModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog" style="max-width: 1442px">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title">Student Class Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Insert other content here kung kailangan -->
                <span>Student:</span> <span style="font-weight: 400" id="assess-modal-student-name"></span>
                <br>
                <span>Teacher:</span> <span style="font-weight: 400" id="assess-modal-teacher-name"></span>

                <form action="" method="POST">
                    <!-- To indicate where to go after adding schedule -->
                    <input type="hidden" id="returnUrl" name="returnUrl" value="">

                    <div class="text-center">
                        <input type="submit" value="Save"
                            style="border-radius: 10px; background: #0f0f0f; padding: 13px 54px; color:white; border:none;">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>