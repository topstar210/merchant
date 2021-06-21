<x-slot name="title">
    Add Agent
</x-slot>
<x-slot name="showBack">
    true
</x-slot>

<div class="container-fluid app-main">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-7 mx-auto">
            <div class="card mt-5">
                <div class="card-header">
                    <h6><i class="ti-info-alt text-info"></i> Complete this form to add a new agent</h6>
                </div>
                <div class="card-body">
                    {{--                    <h1>Hello</h1>--}}
                    <form wire:submit.prevent="submit">
                        <div class="row">
                            <div class="col-lg-6">
                                <x-utils.form.input :key="'first_name'" :js="'lazy'"/>
                            </div>
                            <div class="col-lg-6">
                                <x-utils.form.select :key="'state'" :js="''"/>
                            </div>

                            <div class="col-lg-6">
                                <x-utils.form.select :key="'country'" :js="''" :class="'country'">
                                    @foreach($this->countries as $ctry)
                                        <option
                                            value="{{$ctry->short_name}}" {{strtolower($ctry->short_name) == user()->defaultCountry ? 'selected' : '' }}>{{$ctry->name}}</option>
                                    @endforeach
                                </x-utils.form.select>
                            </div>

                            <div class="col-lg-6">
                                <x-utils.form.select :key="'currency'" :js="''" :class="'currency'">
                                    @foreach($this->currencies as $cur)
                                        <option
                                            value="{{$cur->code}}" {{$cur->id == user()->userDetail->default_currency ? 'selected' : '' }}>{{$cur->name}}</option>
                                    @endforeach
                                </x-utils.form.select>
                            </div>
                            <div class="col-lg-6">
                                <x-utils.form.phone-input :key="'phone'" :js="''">
                                    @foreach($this->countries as $ctry)
                                        <option
                                            value="{{$ctry->phone_code}}"
                                            label="{{$ctry->short_name}}" {{strtolower($ctry->phone_code) == user()->carrierCode ? 'selected' : '' }}>{{$ctry->phone_code}}</option>
                                    @endforeach
                                </x-utils.form.phone-input>
                            </div>
                            <div class="col-lg-12">
                                <x-utils.form.textarea :key="'note'" :js="'lazy'"/>
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        </div>


    </div>

</div>
