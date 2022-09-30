
        <div id="form" style="margin-top: 10px">
            <form action="{{ url('masteritem/store') }}" method="post">
                {{ csrf_field() }}
                <div class="mb-3">
                    <label for="item_name" class="form-label input-runded"> Nama Item </label>
                    <input type="text" class="form-control Background @error('item_name') is-invalid @enderror" name="item_name"
                        value="{{ old('item_name') }}" autofocus>
                    @error('item_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label input-runded"> Stock </label>
                    <input type="text" class="form-control Background @error('stock') is-invalid @enderror" name="stock"
                        value="{{ old('stock') }}">
                    @error('stock')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>

