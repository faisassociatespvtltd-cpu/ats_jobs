<!-- Rating Modal -->
<div class="modal fade" id="ratingModal" tabindex="-1" role="dialog" aria-labelledby="ratingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingModalLabel">Rate User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="reviewed_id" id="rating_reviewed_id">
                    <input type="hidden" name="job_posting_id" id="rating_job_posting_id">
                    
                    <div class="form-group text-center">
                        <label>Select Rating</label>
                        <div class="rating-stars">
                            <input type="radio" name="rating" id="star5" value="5"><label for="star5" title="5 stars">★</label>
                            <input type="radio" name="rating" id="star4" value="4"><label for="star4" title="4 stars">★</label>
                            <input type="radio" name="rating" id="star3" value="3"><label for="star3" title="3 stars">★</label>
                            <input type="radio" name="rating" id="star2" value="2"><label for="star2" title="2 stars">★</label>
                            <input type="radio" name="rating" id="star1" value="1"><label for="star1" title="1 star">★</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="review">Review (Optional)</label>
                        <textarea name="review" class="form-control" rows="3" placeholder="Write your experience..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Rating</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.rating-stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
}
.rating-stars input {
    display: none;
}
.rating-stars label {
    font-size: 30px;
    color: #ccc;
    cursor: pointer;
    margin: 0 5px;
}
.rating-stars input:checked ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #ffca08;
}
</style>
