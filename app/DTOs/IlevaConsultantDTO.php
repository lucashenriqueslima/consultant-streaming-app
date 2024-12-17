<?php

namespace App\DTOs;

use App\Enums\Association;

class IlevaConsultantDTO
{
    //'status', 'name', 'email', 'phone', 'cpf', 'team_code'

    public function __construct(
        public string $status,
        public string $name,
        public string $email,
        public string $phone,
        public string $cpf,
        public string $team_code,
        public string $association,
        public ?string $state_code = null,
        public ?string $city_code = null,
        public ?string $birth_date = null,
        public ?string $address = null,
        public ?string $number = null,
        public ?string $complement = null,
        public ?string $neighborhood = null,
        public ?string $zipcode = null,
        public ?string $bank = null,
        public ?string $agency = null,
        public ?string $operation = null,
        public ?string $account_number = null,
        public ?string $commission = null,
        public ?string $person_type = null,
        public ?string $company_name = null,
        public ?string $representative = null
    ) {}

    //toArray
    public function toArray(): array
    {
        return [
            "status" => $this->status,
            "nome" => $this->name,
            "email" => $this->email,
            "telefone" => $this->phone,
            "cpf" => $this->cpf,
            "cod_equipe" => $this->team_code,
            "cod_estado" => $this->state_code,
            "cod_cidade" => $this->city_code,
            "data_nascimento" => $this->birth_date,
            "rua" => $this->address,
            "numero" => $this->number,
            "complemento" => $this->complement,
            "bairro" => $this->neighborhood,
            "cep" => $this->zipcode,
            "banco" => $this->bank,
            "agencia" => $this->agency,
            "operacao" => $this->operation,
            "conta" => $this->account_number,
            "recebe_comissao" => $this->commission,
            "tipo_pessoa" => $this->person_type,
            "razao_social" => $this->company_name,
            "representante" => $this->representative
        ];
    }
}
