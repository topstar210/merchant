<x-slot name="title">
    Add Agent
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="'Add Agent'" :showBack="'true'" wire:ignore/>
    <div class="container-fluid app-main">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-7 mx-auto">
                <div class="card mt-4">
                    <div class="card-header">
                        <h6><i class="ti-info-alt text-info"></i> Complete this form to add a new agent</h6>
                    </div>
                    <div class="card-body">
                        {{--                    <h1>Hello</h1>--}}
                        <form wire:submit.prevent="addAgent">
                            <div class="row">
                                <div class="col-lg-6">
                                    <x-utils.form.input :key="'first_name'" :js="'defer'"/>
                                </div>
                                <div class="col-lg-6">
                                    <x-utils.form.input :key="'last_name'" :js="'defer'"/>
                                </div>

                                <div class="col-lg-6">
                                    <x-utils.form.select :key="'country'" :js="''" :class="'country'">
                                        @foreach($this->countries as $ctry)
                                            <option
                                                value="{{$ctry->short_name}}"
                                                label="{{$ctry->phone_code}}" {{strtolower($ctry->short_name) == strtolower($country) ? 'selected' : '' }}>{{$ctry->name}}</option>
                                        @endforeach
                                    </x-utils.form.select>
                                </div>

                                <div class="col-lg-6">
                                    <x-utils.form.phone-input :key="'phone'" :js="'defer'" :disabled="''">
                                        @foreach($this->countries as $ctry)
                                            <option
                                                value="{{$ctry->phone_code}}"
                                                label="{{$ctry->short_name}}"
                                                {{$ctry->phone_code == $phone_code ? 'selected' : '' }}>{{$ctry->phone_code}}</option>
                                        @endforeach
                                    </x-utils.form.phone-input>
                                </div>
                                <div class="col-lg-6">
                                    <x-utils.form.input :key="'email'" :type="'email'" :js="'defer'"/>
                                </div>

                                <div class="col-lg-6">
                                    <x-utils.form.radio :key="'gender'" :options="$this->genders ?? []" :js="''"/>
                                </div>

                                <div class="col-lg-6">
                                    <x-utils.form.select :key="'state'" :js="''">
                                        @foreach($this->states as $st)
                                            <option
                                                value="{{$st['name']}}" {{strtolower($st['name']) == strtolower($state) ? 'selected' : '' }}>{{$st['name']}}</option>
                                        @endforeach
                                    </x-utils.form.select>
                                    {{--                                    <x-utils.form.input :key="'state'" :js="'defer'"/>--}}
                                </div>

                                <div class="col-lg-6">
                                    <x-utils.form.input :key="'city'" :js="'defer'"/>
                                </div>

                                <div class="col-lg-12">
                                    <x-utils.form.textarea :key="'address'" :js="'defer'"/>
                                </div>

                                {{--                            <div class="col-lg-12">--}}
                                <div class="d-grid gap-2 col-sm-12 col-md-6 mx-auto">
                                    <button class="btn btn-success" type="submit" wire:target="addAgent"
                                            wire:loading.attr="disabled"><span
                                            wire:target="addAgent" wire:loading class="btn-spinner"></span> Submit
                                    </button>
                                </div>

                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#country').on('change', function (e) {
                var phoneCode = $('#country option:selected').attr('label');
            @this.call('setCountry', [e.target.value, phoneCode]);

                $('#phone_code').val(phoneCode).trigger('change')
            });

            $('#state').on('change', function (e) {
            @this.set('state', e.target.value);
            });
        });

        Livewire.on('updatedStates', (states) => {
            var options = [{
                text: "Select",
                id: ""
            }];
            $.each(states, function (key, value) {
                options.push({
                    text: value.name,
                    id: value.name
                });
            })
            $("#state").empty().select2({
                data: options
            });

        })

    </script>

@endpush
