<?php
// ContractModel.php
declare(strict_types=1);
namespace RMapp\src\Contracts;

use RMapp\helpers\Helpers;

/**
 * Creates a new contract with the provided details.
 *
 * @param int $id The ID of the contract.
 * @param string $descrizione The description of the contract.
 * @param int $oresettimanali The weekly hours of the contract.
 * @param int $giorni_ferie_annuali The annual vacation days of the contract.
 * @param int $ore_permesso_annuali The annual permission hours of the contract.
 * @param string $pausa_inizio_estivo The start time of the summer break.
 * @param string $durata_pausa_estivo The duration of the summer break.
 * @param string $ora_inizio_estivo The start time of the summer work hours.
 * @param string $ora_fine_estivo The end time of the summer work hours.
 * @param string $ora_inizio_inverno The start time of the winter work hours.
 * @param string $ora_fine_inverno The end time of the winter work hours.
 * @param string $pausa_inizio_inverno The start time of the winter break.
 * @param string $durata_pausa_inverno The duration of the winter break.
 * @param bool $cancellato Indicates whether the contract is canceled.
 *
 * @return void
  */
class ContractModel {
  private int $id = 0;
  private string $descrizione;
  private int $oresettimanali = 0;
  private int $giorni_ferie_annuali = 0;
  private int $ore_permesso_annuali = 0;
  private string $pausa_inizio_estivo = '00:00:00';
  private string $durata_pausa_estivo = '00:00:00';
  private string $ora_inizio_estivo = '00:00:00';
  private string $ora_fine_estivo = '00:00:00';
  private string $ora_inizio_inverno = '00:00:00';
  private string $ora_fine_inverno = '00:00:00';
  private string $pausa_inizio_inverno = '00:00:00';
  private string $durata_pausa_inverno = '00:00:00';
  private int $cancellato = 0;
  private array $contract;
  public function __construct() {}

  public function makeContract(
    int $id,
    string $descrizione,
    int $oresettimanali,
    int $giorni_ferie_annuali,
    int $ore_permesso_annuali,
    string $pausa_inizio_estivo,
    string $durata_pausa_estivo,
    string $ora_inizio_estivo,
    string $ora_fine_estivo,
    string $ora_inizio_inverno,
    string $ora_fine_inverno,
    string $pausa_inizio_inverno,
    string $durata_pausa_inverno,
    int $cancellato
  ) {
    $this->id = $id;
    $this->descrizione = $descrizione;
    $this->oresettimanali = $oresettimanali;
    $this->giorni_ferie_annuali = $giorni_ferie_annuali;
    $this->ore_permesso_annuali = $ore_permesso_annuali;
    $this->pausa_inizio_estivo = $pausa_inizio_estivo;
    $this->durata_pausa_estivo = $durata_pausa_estivo;
    $this->ora_inizio_estivo = $ora_inizio_estivo;
    $this->ora_fine_estivo = $ora_fine_estivo;
    $this->ora_inizio_inverno = $ora_inizio_inverno;
    $this->ora_fine_inverno = $ora_fine_inverno;
    $this->pausa_inizio_inverno = $pausa_inizio_inverno;
    $this->durata_pausa_inverno = $durata_pausa_inverno;
    $this->cancellato = $cancellato;
    $this->contract = [
      'id' => $this->id,
      'descrizione' => $this->descrizione,
      'oresettimanali' => $this->oresettimanali,
      'giorni_ferie_annuali' => $this->giorni_ferie_annuali,
      'ore_permesso_annuali' => $this->ore_permesso_annuali,
      'pausa_inizio_estivo' => $this->pausa_inizio_estivo,
      'durata_pausa_estivo' => $this->durata_pausa_estivo,
      'ora_inizio_estivo' => $this->ora_inizio_estivo,
      'ora_fine_estivo' => $this->ora_fine_estivo,
      'ora_inizio_inverno' => $this->ora_inizio_inverno,
      'ora_fine_inverno' => $this->ora_fine_inverno,
      'pausa_inizio_inverno' => $this->pausa_inizio_inverno,
      'durata_pausa_inverno' => $this->durata_pausa_inverno,
      'cancellato' => $this->cancellato
    ];
  }

  public function getID(): int {
    return $this->id;
  }

  public function setID(string $id): void {
    if (Helpers::validatePositiveInt($id) && $this->id == 0) {
      $this->id = (int) $id;
      $this->contract['id'] = $id;
    } else {
      throw new \InvalidArgumentException('Invalid ID');
    }
  }

  public function getDescrizione(): string {
    return $this->descrizione;
  }

  public function setDescrizione(string $descrizione): void {
    $this->descrizione = $descrizione;
    $this->contract['descrizione'] = $descrizione;
  }

  public function getOresettimanali(): int {
    return $this->oresettimanali;
  }

  public function setOresettimanali(int $oresettimanali): void {
    $this->oresettimanali = $oresettimanali;
    $this->contract['oresettimanali'] = $oresettimanali;
  }

  public function getGiorniFerieAnnuali(): int {
    return $this->giorni_ferie_annuali;
  }

  public function setGiorniFerieAnnuali(int $giorni_ferie_annuali): void {
    $this->giorni_ferie_annuali = $giorni_ferie_annuali;
    $this->contract['giorni_ferie_annuali'] = $giorni_ferie_annuali;
  }

  public function getOrePermessoAnnuali(): int {
    return $this->ore_permesso_annuali;
  }

  public function setOrePermessoAnnuali(int $ore_permesso_annuali): void {
    $this->ore_permesso_annuali = $ore_permesso_annuali;
    $this->contract['ore_permesso_annuali'] = $ore_permesso_annuali;
  }

  public function getPausaInizioEstivo(): string {
    return $this->pausa_inizio_estivo;
  }

  public function setPausaInizioEstivo(string $pausa_inizio_estivo): void {
    $this->pausa_inizio_estivo = $pausa_inizio_estivo;
    $this->contract['pausa_inizio_estivo'] = $pausa_inizio_estivo;
  }

  public function getDurataPausaEstivo(): string {
    return $this->durata_pausa_estivo;
  }

  public function setDurataPausaEstivo(string $durata_pausa_estivo): void {
    $this->durata_pausa_estivo = $durata_pausa_estivo;
    $this->contract['durata_pausa_estivo'] = $durata_pausa_estivo;
  }

  public function getOraInizioEstivo(): string {
    return $this->ora_inizio_estivo;
  }

  public function setOraInizioEstivo(string $ora_inizio_estivo): void {
    $this->ora_inizio_estivo = $ora_inizio_estivo;
    $this->contract['ora_inizio_estivo'] = $ora_inizio_estivo;
  }

  public function getOraFineEstivo(): string {
    return $this->ora_fine_estivo;
  }

  public function setOraFineEstivo(string $ora_fine_estivo): void {
    $this->ora_fine_estivo = $ora_fine_estivo;
    $this->contract['ora_fine_estivo'] = $ora_fine_estivo;
  }

  public function getOraInizioInverno(): string {
    return $this->ora_inizio_inverno;
  }

  public function setOraInizioInverno(string $ora_inizio_inverno): void {
    $this->ora_inizio_inverno = $ora_inizio_inverno;
    $this->contract['ora_inizio_inverno'] = $ora_inizio_inverno;
  }

  public function getOraFineInverno(): string {
    return $this->ora_fine_inverno;
  }

  public function setOraFineInverno(string $ora_fine_inverno): void {
    $this->ora_fine_inverno = $ora_fine_inverno;
    $this->contract['ora_fine_inverno'] = $ora_fine_inverno;
  }

  public function getPausaInizioInverno(): string {
    return $this->pausa_inizio_inverno;
  }

  public function setPausaInizioInverno(string $pausa_inizio_inverno): void {
    $this->pausa_inizio_inverno = $pausa_inizio_inverno;
    $this->contract['pausa_inizio_inverno'] = $pausa_inizio_inverno;
  }

  public function getDurataPausaInverno(): string {
    return $this->durata_pausa_inverno;
  }

  public function setDurataPausaInverno(string $durata_pausa_inverno): void {
    $this->durata_pausa_inverno = $durata_pausa_inverno;
    $this->contract['durata_pausa_inverno'] = $durata_pausa_inverno;
  }

  public function isCancellatoBool(): bool {
    if ($this->cancellato == 1) {
      return true;
    }
    return false;
  }

  public function setCancellatobool(bool $cancellato): void {
    if ($cancellato) {
      $this->cancellato = 1;
    } else {
      $this->cancellato = 0;
    }
    $this->contract['cancellato'] = $this->cancellato;
  }

  public function isCancellatoInt(): int {
    return $this->cancellato;
  }

  public function setCancellatoInt(int $cancellato): void {
    if ($cancellato == 0) {
      $this->cancellato = 0;
      $this->contract['cancellato'] = $this->cancellato;
    } else if ($cancellato == 1) {
      $this->cancellato = 1;
      $this->contract['cancellato'] = $this->cancellato;
    }
  }

  public function getContract(): array {
    return $this->contract;
  }

}
?>