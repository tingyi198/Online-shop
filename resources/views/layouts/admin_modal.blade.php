<div class="modal fade" id="upload_image" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">上傳圖片</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="/admin/products/upload-image" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="product_id" id="product_id">
                    <input type="file" name="product_image" id="product_image">
                    <input type="submit" value="送出">
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="import" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">匯入 Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="/admin/products/excel/import" method="POST" enctype="multipart/form-data">
                    <input type="file" name="excel" id="excel">
                    <input type="submit" value="送出">
                </form>
            </div>

        </div>
    </div>
</div>
