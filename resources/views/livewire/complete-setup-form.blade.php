<div>


    <x-utils.form.input :key="'password'" :label="'Choose Password'" :type="'password'" :js="''"/>
    <x-utils.form.input :key="'password_confirmation'" :label="'Confirm Password'" :type="'password'" :js="''"/>
    <x-utils.form.input :key="'pin'" :label="'Transaction PIN'" :type="'number'" :js="''"/>

    <div class="form-group mb-0 row">
        <div class="col-12">
            <button class="btn btn-success w-100 waves-effect waves-light" @if($errors->any()) disabled @endif
            type="submit">{{ __('Continue') }}
            </button>
        </div><!--end col-->
    </div>
</div>
