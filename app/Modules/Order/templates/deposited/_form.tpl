{set $model = $form->getInstance()}
<table width="100%" cellspacing="3" cellpadding="3">
    <tbody>
    <tr>
        <td valign="top" nowrap="nowrap">
            {raw $form->getField('check_date')->render()}
        </td>
        <td>&nbsp;&nbsp;</td>
        <td valign="top">
            {$form->getField('currency')->render()}
        </td>
        <td>&nbsp;&nbsp;</td>
        <td valign="top" nowrap="nowrap">
            <b>Deposit status:</b><br>
            <div style="padding-top: 3px;">{$model->getField('status')->toText()}</div>
        </td>
        <td width="90%" valign="top" align="right">
            <a href="{$admin->getAllUrl()}" style="color: blue;">Back to List of deposits</a>
        </td>
    </tr>
    </tbody>
</table>
{if $model->pk && $model->orders->count()}
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td style="padding: 5px 0">This deposit contains checks for the following orders:</td>
        </tr>
        {$form->getField('orders')->render()}
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <div style="font-weight: bold; font-size: 14px;">
                    Total deposit amount:
                    <span style="margin-left:85px;">{$form->getField('total_deposit_amount')->getValue()}</span>
                </div>
            </td>
        </tr>
    </table>
{/if}
<table width="100%" cellspacing="1" cellpadding="3">

    <tbody>
    <tr>
        <td colspan="5">This deposit contains checks for the following orders:</td>
    </tr>

    <tr class="TableHead">
        <td width="75">Order #</td>
        <td width="150"> Customer Check #</td>
        <td width="70">Amount</td>
        <td width="*">Internal Notes</td>
        <td style="background: #ffffff;"></td>
    </tr>

    <tr>
        <td colspan="5"><br>
            <table class="SubHeader" cellspacing="0">
                <tbody>
                <tr>
                    <td class="Green2">Add customer checks received</td>
                </tr>
                <tr>
                    <td class="SubHeaderLine"><img src="/skin1_kolin/images/spacer.gif" class="Spc" alt=""><br></td>
                </tr>
                </tbody>
            </table>

        </td>
    </tr>

    <tr class="add_deposit">
        <td class="add_deposit__orderid" align="center">
            <input type="text" size="8" name="add_orderids[]"></td>
        <td class="add_deposit__number" align="center">
            <input type="text" size="9" name="add_check_numbers[]" style="width: 90%;">
        </td>
        <td class="add_deposit__amount" align="center">
            {ignore}
                <input type="text" pattern="\d+(\.\d{2})?" name="add_amounts[]" size="7">
            {/ignore}
        </td>
        <td class="add_deposit__notes" align="center">
            <input type="text" size="9" name="add_notes[]" style="width: 98%;">
        </td>
        <td class="add_deposit__add" width="30">
            <a href="">
                <img src="/skin1_kolin/images/plus.gif" alt="Add">
            </a>
        </td>
    </tr>

    <tr>
        <td colspan="5">&nbsp;</td>
    </tr>

    <tr>
        <td colspan="2"><span style="font-weight: bold; font-size: 14px;">Total deposit amount:</span></td>
        <td><span style="font-weight: bold; font-size: 14px;" id="Total_deposit_amount_id">0.00</span></td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td colspan="5">
            <div class="row" style="margin-top: 15px;">
                <div class="text-left">
                    <button name="save" type="submit" value="save-stay">Apply changes</button>
                </div>
            </div>
        </td>
    </tr>
</table>
{ignore}
    <script>
        function addNewRow(e) {
            e.preventDefault();
            const lastRow = $('.add_deposit').last()
            const newRow = lastRow.clone()
            newRow.find('input').val('').end()
            newRow.insertAfter(lastRow)
        }

        function calculateTotal() {
            let allInputs = [];
            document.querySelectorAll('.add_deposit__amount > input').forEach((el) => {
                const val = parseFloat(el.value);
                if (!isNaN(val)) {
                    allInputs.push(val);
                }
            })
            let arr = 0;
            if (allInputs.length >= 1) {
                arr = allInputs.reduce((sum, value) => sum + value)
            }
            const sum = parseFloat(arr)
            const total = isNaN(sum) ? 0 : sum
            document.getElementById('Total_deposit_amount_id').innerText = total.toLocaleString('en-US', {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
                currency: 'USD'
            })
        }

        function formatNumber(n) {
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "")
        }

        function formatCurrency(input, blur) {
            input = $(input)
            let input_val = input.val();
            if (input_val === "") {
                return;
            }
            let original_len = input_val.length;
            let caret_pos = input.prop("selectionStart");
            if (input_val.indexOf(".") >= 0) {
                let decimal_pos = input_val.indexOf(".");
                let left_side = input_val.substring(0, decimal_pos);
                let right_side = input_val.substring(decimal_pos);
                left_side = formatNumber(left_side);
                right_side = formatNumber(right_side);
                if (blur === "blur") {
                    right_side += "00";
                }
                right_side = right_side.substring(0, 2);
                input_val = "" + left_side + "." + right_side;

            } else {
                input_val = formatNumber(input_val);
            }
            input.val(input_val);
            let updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }

        $(document).on('keyup', '.add_deposit__amount > input', function () {
            formatCurrency(this)
            calculateTotal()
        })
            .on('blur', '.add_deposit__amount > input', function () {
                formatCurrency(this, "blur");
            })
            .on('click', '.add_deposit__add > a', (e) => addNewRow(e))
    </script>
{/ignore}