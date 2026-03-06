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
                <div style="margin-bottom: 10px;"><span id="assess-modal-student-label">Student:</span> <span
                        style="font-weight: 400" id="assess-modal-student-name"></span></div>

                <div style="margin-bottom: 10px;"><span id="assess-modal-teacher-label">Teacher:</span> <span
                        style="font-weight: 400" id="assess-modal-teacher-name"></span></div>

                <form action="../utils/update-assessment.php" method="POST" enctype="multipart/form-data">
                    <!-- Indicate where to go after adding assessment report -->
                    <input type="hidden" id="assessmentReturnUrl" name="returnUrl" value="">
                    <input type="hidden" id="bookingRefNum" name="bookingRefNum" value="">

                    <div style="margin-bottom: 10px; display: flex; flex-direction: column; gap: 10px">
                        <label for="assessmentReport">Assessment Report:</label>
                        <textarea id="assessmentReport" name="assessmentReport"
                            style="height: 500px; padding: 8px;"></textarea>

                        <label id="assess-modal-file-label" for="assessmentFile">Upload File:</label>
                        <input type="file" name="assessmentFile" id="assessmentFile">
                        <div id="assessmentExistingFile"></div>

                    </div>
                    <div class="text-center">
                        <input type="submit" value="Save" id="assessment-button"
                            style="border-radius: 10px; padding: 13px 54px; color:white; border:none;">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>