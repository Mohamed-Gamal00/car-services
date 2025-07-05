<div>

    <div class="card-body p-4">
        <div class="p-3">
            <form class="mt-4" wire:submit.prevent="submit">

                <div class="mb-3">
                    <label class="form-label" for="email">البريد الإلكتروني</label>
                    <input type="text" class="form-control" id="email" placeholder="ادخل البريد الالكتروني"
                        wire:model="email">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="userpassword">الرقم السري</label>
                    <input type="password" class="form-control" id="userpassword" placeholder="ادخل الرقم السري"
                        wire:model="password">
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="customControlInline"
                                wire:model="remember">
                            <label class="form-check-label" for="customControlInline">Remember
                                me</label>
                        </div>
                    </div>

                    <div class="col-sm-6 text-end" wire:loading wire:target="submit">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <div class="col-sm-6 text-end" wire:loading.remove>
                        <button class="btn btn-primary w-md waves-effect waves-light" type="submit">
                            <span>
                                تسجل الدخول
                            </span>
                        </button>
                    </div>
                </div>

        </div>


        </form>

    </div>
</div>
</div>
