<!-- Login Form -->
<div class="row g-4 mb-5">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-box-arrow-in-right me-2 text-primary"></i>
                    Login
                </h5>
            </div>
            <div class="card-body">
                <form x-data="loginForm" method="post" action="<?= BASE_PATH ?>/api/login">
                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" class="form-control"
                                x-model="form.username" @input="validateField('username')"
                                :class="getFieldClass('username')" placeholder="your username"
                                required>
                        </div>
                        <div class="invalid-feedback d-block" x-show="errors.username"
                            x-text="errors.username"></div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control"
                                x-model="form.password" @input="validateField('password')"
                                :class="getFieldClass('password')" placeholder="your password"
                                required>
                        </div>
                        <div class="invalid-feedback d-block" x-show="errors.password"
                            x-text="errors.password"></div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg"
                            :disabled="isSubmitting || !isFormValid">
                            <span x-show="!isSubmitting">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </span>
                            <span x-show="isSubmitting">
                                <div class="spinner-border spinner-border-sm me-2"></div>
                                Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
