<div class="giornata">
    <label>Data:</label>
    <input type="date" 
           name="giornate_{{ $tipo }}[{{ $index }}][data]" 
           value="{{ old("giornate_{$tipo}.{$index}.data", $giornata?->get('data', '')) }}" 
           required>

    <label>Orario Inizio:</label>
    <input type="time" 
           name="giornate_{{ $tipo }}[{{ $index }}][orarioInizio]" 
           value="{{ old("giornate_{$tipo}.{$index}.orarioInizio", $giornata?->get('orarioInizio', '')) }}" 
           required>

    <label>Orario Fine:</label>
    <input type="time" 
           name="giornate_{{ $tipo }}[{{ $index }}][orarioFine]" 
           value="{{ old("giornate_{$tipo}.{$index}.orarioFine", $giornata?->get('orarioFine', '')) }}" 
           required>

    <label>Minuti Totali:</label>
    <input type="number" 
           name="giornate_{{ $tipo }}[{{ $index }}][minutiTotali]" 
           value="{{ old("giornate_{$tipo}.{$index}.minutiTotali", $giornata?->get('minutiTotali', '')) }}" 
           min="0" required>

    <label>Numero Risorse Richieste:</label>
    <input type="number" 
           name="giornate_{{ $tipo }}[{{ $index }}][numeroRisorseRichieste]" 
           value="{{ old("giornate_{$tipo}.{$index}.numeroRisorseRichieste", $giornata?->get('numeroRisorseRichieste', '')) }}" 
           min="1" required>

    <button type="button" onclick="this.parentElement.remove()">Rimuovi</button>
</div>
