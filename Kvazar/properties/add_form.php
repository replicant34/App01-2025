<div class="form-row">
    <!-- Company Information Column -->
    <div class="form-column">
        <h2 class="column-header">Company Information</h2>
        
        <div class="form-group">
            <label for="company_type">Company Type</label>
            <select id="company_type" name="company_type" required>
                <option value="ООО">ООО</option>
                <option value="ИП">ИП</option>
                <option value="Самозанятый">Самозанятый</option>
            </select>
        </div>

        <div class="form-group">
            <label for="full_company_name">Full Company Name</label>
            <input type="text" id="full_company_name" name="full_company_name" required placeholder="Enter full company name">
        </div>

        <div class="form-group">
            <label for="short_company_name">Short Company Name</label>
            <input type="text" id="short_company_name" name="short_company_name" placeholder="Enter short company name">
        </div>

        <div class="form-group">
            <label for="inn">INN</label>
            <input type="text" id="inn" name="inn" placeholder="Enter INN">
        </div>

        <div class="form-group">
            <label for="kpp">KPP</label>
            <input type="text" id="kpp" name="kpp" placeholder="Enter KPP">
        </div>

        <div class="form-group">
            <label for="ogrn">OGRN</label>
            <input type="text" id="ogrn" name="ogrn" placeholder="Enter OGRN">
        </div>

        <div class="form-group">
            <label for="physical_address">Physical Address</label>
            <input type="text" id="physical_address" name="physical_address" placeholder="Enter physical address">
        </div>

        <div class="form-group">
            <label for="legal_address">Legal Address</label>
            <input type="text" id="legal_address" name="legal_address" placeholder="Enter legal address">
        </div>
    </div>

    <!-- Contact and Bank Information Column -->
    <div class="form-column">
        <h2 class="column-header">Contact & Bank Details</h2>


        <div class="form-group">
            <label for="bank_name">Bank Name</label>
            <input type="text" id="bank_name" name="bank_name" class="autocomplete" placeholder="Enter bank name">
        </div>

        <div class="form-group">
            <label for="bik">BIK</label>
            <input type="text" id="bik" name="bik" class="autocomplete" placeholder="Enter BIK">
        </div>

        <div class="form-group">
            <label for="settlement_account">Settlement Account</label>
            <input type="text" id="settlement_account" name="settlement_account" placeholder="Enter settlement account">
        </div>

        <div class="form-group">
            <label for="correspondent_account">Correspondent Account</label>
            <input type="text" id="correspondent_account" name="correspondent_account" placeholder="Enter correspondent account">
        </div>

        <div class="form-group">
            <label for="contact_person">Contact Person</label>
            <input type="text" id="contact_person" name="contact_person" placeholder="Enter contact person">
        </div>

        <div class="form-group">
            <label for="contact_person_position">Contact Person Position</label>
            <input type="text" id="contact_person_position" name="contact_person_position" class="autocomplete" placeholder="Enter contact person position">
        </div>

        <div class="form-group">
            <label for="contact_person_phone">Contact Person Phone</label>
            <input type="text" id="contact_person_phone" name="contact_person_phone" placeholder="Enter contact person phone">
        </div>

        <div class="form-group">
            <label for="contact_person_email">Contact Person Email</label>
            <input type="email" id="contact_person_email" name="contact_person_email" placeholder="Enter contact person email">
        </div>

        <div class="form-group">
            <label for="head_position">Head Position</label>
            <input type="text" id="head_position" name="head_position" class="autocomplete" placeholder="Enter head position">
        </div>

        <div class="form-group">
            <label for="head_name">Head Name</label>
            <input type="text" id="head_name" name="head_name" placeholder="Enter head name">
        </div>
    </div>
</div>