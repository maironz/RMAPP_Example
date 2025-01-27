<?php
// Contracts.php
// This file displays the contracts view


for ($i = 0; $i < count($contratti); $i++) {
  $contratti[$i]['ora_inizio_estivo_ore'] = str_pad((string)intval(substr($contratti[$i]['ora_inizio_estivo'], 0, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['ora_inizio_estivo_minuti'] = str_pad((string)intval(substr($contratti[$i]['ora_inizio_estivo'], 3, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['ora_fine_estivo_ore'] = str_pad((string)intval(substr($contratti[$i]['ora_fine_estivo'], 0, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['ora_fine_estivo_minuti'] = str_pad((string)intval(substr($contratti[$i]['ora_fine_estivo'], 3, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['pausa_inizio_estivo_ore'] = str_pad((string)intval(substr($contratti[$i]['pausa_inizio_estivo'], 0, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['pausa_inizio_estivo_minuti'] = str_pad((string)intval(substr($contratti[$i]['pausa_inizio_estivo'], 3, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['durata_pausa_estivo_ore'] = str_pad((string)intval(substr($contratti[$i]['durata_pausa_estivo'], 0, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['durata_pausa_estivo_minuti'] = str_pad((string)intval(substr($contratti[$i]['durata_pausa_estivo'], 3, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['ora_inizio_inverno_ore'] = str_pad((string)intval(substr($contratti[$i]['ora_inizio_inverno'], 0, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['ora_inizio_inverno_minuti'] = str_pad((string)intval(substr($contratti[$i]['ora_inizio_inverno'], 3, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['ora_fine_inverno_ore'] = str_pad((string)intval(substr($contratti[$i]['ora_fine_inverno'], 0, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['ora_fine_inverno_minuti'] = str_pad((string)intval(substr($contratti[$i]['ora_fine_inverno'], 3, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['pausa_inizio_inverno_ore'] = str_pad((string)intval(substr($contratti[$i]['pausa_inizio_inverno'], 0, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['pausa_inizio_inverno_minuti'] = str_pad((string)intval(substr($contratti[$i]['pausa_inizio_inverno'], 3, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['durata_pausa_inverno_ore'] = str_pad((string)intval(substr($contratti[$i]['durata_pausa_inverno'], 0, 2)), 2, '0', STR_PAD_LEFT);
  $contratti[$i]['durata_pausa_inverno_minuti'] = str_pad((string)intval(substr($contratti[$i]['durata_pausa_inverno'], 3, 2)), 2, '0', STR_PAD_LEFT);
}

$css1_version = filemtime(__DIR__ . '/css/RMTemplate.css');
?>
<link rel="stylesheet" href="/../runner/RMapp/public/css/RMTemplate.css?v=<?php echo $css1_version; ?>">
<link rel="stylesheet" type="text/css" href="https://common.olemiss.edu/_js/sweet-alert/sweet-alert.css">
<h4><?php echo $langHelperPHP->translate("contracts_title") ?></h4>
<h5><?php echo $langHelperPHP->translate("contracts_title_alert") ?></h5>
<h5><?php echo $langHelperPHP->translate("contracts_alert_note") ?></h5>
<body>
  <span style="margin-right:10px"><button id="crea-contratto-btn"  onclick="newContract()"><?php echo $langHelperPHP->translate("make_contract") ?></button></span>
  <div class="table_container">
    <table id="tabella_contratti" class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th style="width: 5%; height: 3em;">ID</th>
          <th style="width: 35%;min-width: 500px;"><?php echo $langHelperPHP->translate("description") ?></th>
          <th style="width: 5%;"><?php echo $langHelperPHP->translate("weekly_hours") ?></th>
          <th style="width: 5%;"><?php echo $langHelperPHP->translate("annual_leave_days") ?></th>
          <th style="width: 5%;"><?php echo $langHelperPHP->translate("annual_leave_hours") ?></th>
          <th style="min-width: 95px;"><?php echo $langHelperPHP->translate("summer_start_time") ?></th>
          <th style="min-width: 95px;"><?php echo $langHelperPHP->translate("summer_end_time") ?></th>
          <th style="min-width: 95px;"><?php echo $langHelperPHP->translate("summer_break_start") ?></th>
          <th style="min-width: 95px;"><?php echo $langHelperPHP->translate("summer_break_duration") ?></th>
          <th style="min-width: 95px;"><?php echo $langHelperPHP->translate("winter_start_time") ?></th>
          <th style="min-width: 95px;"><?php echo $langHelperPHP->translate("winter_end_time") ?></th>
          <th style="min-width: 95px;"><?php echo $langHelperPHP->translate("winter_break_start") ?></th>
          <th style="min-width: 95px;"><?php echo $langHelperPHP->translate("winter_break_duration") ?></th>
          <th style="min-width: 5%;"><?php echo $langHelperPHP->translate("deleted") ?></th>
        </tr>
      </thead>
      <tbody>
        <?php for ($i = 0; $i < count($contratti); $i++){?>
          <tr data-id="<?php echo $i + 1; ?>">
            <td data-field="ID"><?php echo $contratti[$i]['id']; ?></td>
            <td contenteditable="true" id="descrizione<?echo $contratti[$i]['id']?>" data-field="descrizione"><?php echo htmlspecialchars($contratti[$i]['descrizione']); ?></td>
            <td contenteditable="true" id="oresettimanali<?echo $contratti[$i]['id']?>" data-field="oresettimanali"><?php echo htmlspecialchars($contratti[$i]['oresettimanali']); ?></td>
            <td contenteditable="true" id="giorni_ferie_annuali<?echo $contratti[$i]['id']?>" data-field="giorni_ferie_annuali"><?php echo htmlspecialchars($contratti[$i]['giorni_ferie_annuali']); ?></td>
            <td contenteditable="true" id="ore_permesso_annuali<?echo $contratti[$i]['id']?>" data-field="ore_permesso_annuali"><?php echo htmlspecialchars($contratti[$i]['ore_permesso_annuali']); ?></td>
            <td>
              <div class="select-container">
                <select class="select-custom" id="ora_inizio_estivo_ore<?echo $contratti[$i]['id']?>" data-field="ora_inizio_estivo_ore">
                  <?php for ($j = 0; $j < 24; $j++): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['ora_inizio_estivo_ore']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
              <br>
              <div class="select-container">
                <select class="select-custom" id="ora_inizio_estivo_minuti<?echo $contratti[$i]['id']?>" data-field="ora_inizio_estivo_minuti">
                  <?php for ($j = 0; $j < 60; $j += 15): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['ora_inizio_estivo_minuti']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
            </td>
            <td>
              <div class="select-container">
                <select class="select-custom" id="ora_fine_estivo_ore<?echo $contratti[$i]['id']?>" data-field="ora_fine_estivo_ore">
                  <?php for ($j = 0; $j < 24; $j++): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['ora_fine_estivo_ore']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
              <br>
              <div class="select-container">
                <select class="select-custom" id="ora_fine_estivo_minuti<?echo $contratti[$i]['id']?>" data-field="ora_fine_estivo_minuti">
                  <?php for ($j = 0; $j < 60; $j += 15): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['ora_fine_estivo_minuti']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
            </td>
            <td>
              <div class="select-container">
                <select class="select-custom" id="pausa_inizio_estivo_ore<?echo $contratti[$i]['id']?>" data-field="pausa_inizio_estivo_ore">
                  <?php for ($j = 0; $j < 24; $j++): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['pausa_inizio_estivo_ore']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
              <br>
              <div class="select-container">
                <select class="select-custom" id="pausa_inizio_estivo_minuti<?echo $contratti[$i]['id']?>" data-field="pausa_inizio_estivo_minuti">
                  <?php for ($j = 0; $j < 60; $j += 15): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['pausa_inizio_estivo_minuti']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
            </td>
            <td>
              <div class="select-container">
                <select class="select-custom" id="durata_pausa_estivo_ore<?echo $contratti[$i]['id']?>" data-field="durata_pausa_estivo_ore">
                  <?php for ($j = 0; $j < 24; $j++): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['durata_pausa_estivo_ore']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
              <br>
              <div class="select-container">
                <select class="select-custom" id="durata_pausa_estivo_minuti<?echo $contratti[$i]['id']?>" data-field="durata_pausa_estivo_minuti">
                  <?php for ($j = 0; $j < 60; $j += 15): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['durata_pausa_estivo_minuti']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
            </td>
            <td>
              <div class="select-container">
                <select class="select-custom" id="ora_inizio_inverno_ore<?echo $contratti[$i]['id']?>" data-field="ora_inizio_inverno_ore">
                  <?php for ($j = 0; $j < 24; $j++): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['ora_inizio_inverno_ore']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
              <br>
              <div class="select-container">
                <select class="select-custom" id="ora_inizio_inverno_minuti<?echo $contratti[$i]['id']?>" data-field="ora_inizio_inverno_minuti">
                  <?php for ($j = 0; $j < 60; $j += 15): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['ora_inizio_inverno_minuti']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
            </td>
            <td>
              <div class="select-container">
                <select class="select-custom" id="ora_fine_inverno_ore<?echo $contratti[$i]['id']?>" data-field="ora_fine_inverno_ore">
                  <?php for ($j = 0; $j < 24; $j++): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['ora_fine_inverno_ore']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
              <br>
              <div class="select-container">
                <select class="select-custom" id="ora_fine_inverno_minuti<?echo $contratti[$i]['id']?>" data-field="ora_fine_inverno_minuti">
                  <?php for ($j = 0; $j < 60; $j += 15): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['ora_fine_inverno_minuti']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
            </td>
            <td>
              <div class="select-container">
                <select class="select-custom" id="pausa_inizio_inverno_ore<?echo $contratti[$i]['id']?>" data-field="pausa_inizio_inverno_ore">
                  <?php for ($j = 0; $j < 24; $j++): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['pausa_inizio_inverno_ore']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
              <br>
              <div class="select-container">
                <select class="select-custom" id="pausa_inizio_inverno_minuti<?echo $contratti[$i]['id']?>" data-field="pausa_inizio_inverno_minuti">
                  <?php for ($j = 0; $j < 60; $j += 15): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['pausa_inizio_inverno_minuti']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
            </td>
            <td>
              <div class="select-container">
                <select class="select-custom" id="durata_pausa_inverno_ore<?echo $contratti[$i]['id']?>" data-field="durata_pausa_inverno_ore">
                  <?php for ($j = 0; $j < 24; $j++): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['durata_pausa_inverno_ore']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
              <br>
              <div class="select-container">
                <select class="select-custom" id="durata_pausa_inverno_minuti<?echo $contratti[$i]['id']?>" data-field="durata_pausa_inverno_minuti">
                  <?php for ($j = 0; $j < 60; $j += 15): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($j == $contratti[$i]['durata_pausa_inverno_minuti']) ? 'selected' : ''; ?>>
                      <?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
            </td>
            <td contenteditable="true" id="cancellato<?echo $contratti[$i]['id']?>" data-field="cancellato"><?php echo htmlspecialchars($contratti[$i]['cancellato']); ?></td>
          </tr>
        <?php }; ?>
      </tbody>
    </table>
    <button class="scroll-button" id="scroll-button">
      <i class="fas fa-arrow-right"></i>
    </button>
  </div>
</body>
<div id="output-box" sessionid="<?php echo session_id();?>" sessionname="<?php echo session_name();?>" style></div>
<?php
//verifica se necessario aggiornare i file
$js1_version = filemtime(__DIR__ . '/js/RMAjaxManager.js');
$js3_version = filemtime(__DIR__ . '/js/Contract.js');
$js4_version = filemtime(__DIR__ . '/js/RMTemplateGraphics.js');
$js5_version = filemtime(__DIR__ . '/js/RMTranslationHelpers.js');
$js6_version = filemtime(__DIR__ . '/js/RMHelpers.js');?>
<script> const translations = <?= json_encode($langhelperJS->getTranslations()) ?>; </script>
<script src="/../runner/RMapp/public/js/RMTranslationHelpers.js?v=<?php echo $js5_version; ?>"></script>
<script src="/../runner/RMapp/public/js/RMHelpers.js?v=<?php echo $js6_version; ?>"></script>
<script src="/../runner/RMapp/public/js/RMAjaxManager.js?v=<?php echo $js1_version; ?>"></script>
<script src="/../runner/RMapp/public/js/RMTemplateGraphics.js?v=<?php echo $js4_version; ?>"></script>
<script src="/../runner/RMapp/public/js/Contract.js?v=<?php echo $js3_version; ?>"></script>


