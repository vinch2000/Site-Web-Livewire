@extends('layouts.app')

@section('content')
    @php $disabled = ($action == "consulter") ? 'disabled' : ''; @endphp
    <div class="container">
        <h2>{{ (isset($bien)) ? $bien->lib : '' }}</h2>
    </div>
    <div class="container card p-3">
        <form method="post" action="{{ ($action == "editer") ? route('biens.update', ["id" => $bien->id]) : route('biens.store') }}" enctype="multipart/form-data">
            @csrf
            @if($action == "editer")
                @method('PUT')
            @endif
            <div class="row">
                <div class="col-lg-5">
                    <label for="photo">Photo</label>
                    <input type="file" class="form-control  @error('photo') is-invalid @enderror" id="photo" name="photo">
                    @error('photo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    @if($action != 'ajouter')
                        <br>
                        <img src="{{ old('photo', $bien->photo ?? '/images/default.jpg') }}" class="img-fluid rounded">
                    @endif
                </div>
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-7 mb-3 mb-lg-0">
                            <input type="text" class="form-control @error('lib') is-invalid @enderror" id="inputLib" name="lib" placeholder="Libellé du bien" value="{{ old('lib', $bien->lib ?? '') }}" {{ $disabled }}>
                            @error('lib')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-lg-5">
                            <select name="type_annonce_id" class="form-select @error('type_annonce_id') is-invalid @enderror" {{ $disabled }}>
                                @foreach($typeAnnonce as $annonce)
                                    <option value="{{ $annonce->id }}" {{ (old('type_annonce_id', $bien->type_annonce_id ?? '') == $annonce->id) ? 'selected' : '' }}>
                                        {{ $annonce->type_annonce }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_annonce_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <select name="type_bien_id"  class="form-select @error('type_bien_id') is-invalid @enderror" {{ $disabled }}>
                                @foreach($typeBien as $leBien)
                                    <option value="{{ $leBien->id }}" {{ (old('type_bien_id', $bien->type_bien_id ?? '') == $leBien->id) ? 'selected' : '' }}>
                                        {{ $leBien->type_bien }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_bien_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <div class="row">
                                <div class="col-auto">
                                    <label for="inputPrix" class="visually-hidden">Prix €</label>
                                    <input type="text" style="width:150px" class="form-control @error('prix') is-invalid @enderror" id="inputPrix" name="prix" placeholder="Prix en €" value="{{ old('prix', isset($bien) ? number_format($bien->prix, 2, '.', '') : '') }}" {{ $disabled }}>
                                    @error('prix')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-auto d-flex align-items-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="sold" value="1" {{ old('sold', $bien->sold ?? '') == '1' ? 'checked' : '' }} {{ $disabled }}>
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Vendu / Loué</label>
                                        @error('sold')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-0 mt-lg-3">
                        <div class="col-12">
                            <textarea name="description" class="form-control" placeholder="Description du bien" style="height: 100px" {{ $disabled }}>{{ old('description', $bien->description ?? '') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <select name="classe_energie" class="form-select @error('classe_energie') is-invalid @enderror" {{ $disabled }}>
                                @foreach($classeEnergie as $key => $value)
                                    <option value="{{ $key }}" {{ (old('classe_energie', $bien->classe_energie ?? '') == $key) ? 'selected' : '' }}>Classe energétique : {{ $value }}</option>
                                @endforeach
                            </select>
                            @error('classe_energie')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="offset-lg-2 col-lg-4">
                            <input name="sh" type="text" class="form-control @error('sh') is-invalid @enderror" placeholder="Superficie habitables en m²" aria-label="Superficie habitables en m²" value="{{ old('sh', $bien->sh ?? '') }}" {{ $disabled }}>
                            @error('sh')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-4 mb-3 mb-lg-0">
                                    <input name="chambre" type="text" class="form-control @error('chambre') is-invalid @enderror" placeholder="Nombre de chambre" aria-label="Nombre de chambre" value="{{ old('chambre', $bien->chambre ?? '') }}" {{ $disabled }}>
                                    @error('chambre')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-lg-4 mb-3 mb-lg-0">
                                    <input name="sdb" type="text" class="form-control @error('sdb') is-invalid @enderror" placeholder="Salle de bains" aria-label="Salle de bains" value="{{ old('sdb', $bien->sdb ?? '') }}" {{ $disabled }}>
                                    @error('sdb')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-lg-4 mb-3 mb-lg-0">
                                    <input name="wc" type="text" class="form-control @error('wc') is-invalid @enderror" placeholder="WC" aria-label="WC" value="{{ old('wc', $bien->wc ?? '') }}" {{ $disabled }}>
                                    @error('wc')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <input name="st" type="text" class="form-control @error('st') is-invalid @enderror" placeholder="Superficie terrain en m²" aria-label="Superficie terrain en m²" value="{{ old('st', $bien->st ?? '') }}" {{ $disabled }}>
                            @error('st')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    @if($action != 'consulter')
                        <div class="row mt-3">
                            <div class="offset-lg-8 col-lg-4">
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-3 mb-lg-0">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg> Valider
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        @php
                                        $url = route('biens.ajout');

                                        if($action != "ajouter")
                                            $url = route('biens.edit', ["id" => $bien->id]);
                                        @endphp
                                        <a href="{{ $url }}" class="btn btn-secondary w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M367.2 412.5L99.5 144.8C77.1 176.1 64 214.5 64 256c0 106 86 192 192 192c41.5 0 79.9-13.1 111.2-35.5zm45.3-45.3C434.9 335.9 448 297.5 448 256c0-106-86-192-192-192c-41.5 0-79.9 13.1-111.2 35.5L412.5 367.2zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z"/></svg> Annuler
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="offset-lg-8 col-lg-4">
                                <a href="{{ route("biens.index") }}" class="btn btn-secondary justify-content-right mt-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M40 48C26.7 48 16 58.7 16 72v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V72c0-13.3-10.7-24-24-24H40zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zM16 232v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V232c0-13.3-10.7-24-24-24H40c-13.3 0-24 10.7-24 24zM40 368c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V392c0-13.3-10.7-24-24-24H40z"/></svg> Retour à la liste
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection