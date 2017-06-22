var MyCenter = function() {
    var optionx = {
    };
    var dnow = new Date();
    var dparse = Date.parse("2016-04-12 00:00:00");

    return {
        init: function(op) {
            optionx = $.extend({}, optionx, op);

            var sidx = null;
            var school = null;

            var $selSchoolID = $('#selSchoolID');
            $('#selStates').on('change', function() {
                var workyear_note = $("#selStates").find("option:selected").text();
                $("#selStates_note").val(workyear_note);
                $("#selSchoolID_note").val("");
                var sid = parseInt($('#selStates option:selected').attr('value'));
                if (sid !== null) {
                    $selSchoolID.html('').append('<option value="0">请选择所在大学</option>');
                    if (sid > 0) {
                        sid = sid - 1;
                        //optionx.schoolsListArray[sid]
                        for (var i = 0; i < optionx.schoolsListArray[sid].length; i++) {
                            $selSchoolID.append('<option value="' + optionx.schoolsListArray[sid][i].ID + '">' + optionx.schoolsListArray[sid][i].Name + '</option>');
                        }
                    }
                }
            });
            $('#selSchoolID').on('change', function() {
                var workyear_note = $("#selSchoolID").find("option:selected").text();
                $("#selSchoolID_note").val(workyear_note);
            });
            if (optionx.schoolID != null) {
                for (var x = 0; x < optionx.schoolsListArray.length; x++) {
                    for (var y = 0; y < optionx.schoolsListArray[x].length; y++) {
                        if (optionx.schoolsListArray[x][y].Name == optionx.schoolID) {
                            sidx = x;
                            school = optionx.schoolsListArray[x][y];
                            break;
                        }
                    }
                    if (school != null)
                        break;
                }

                if (school != null) {
                    $selSchoolID.append('<option value="0">请选择所在大学</option>');
                    for (var i = 0; i < optionx.schoolsListArray[sidx].length; i++) {
                        var selected = '';
                        if (optionx.schoolsListArray[sidx][i].Name == optionx.schoolID)
                            selected = 'selected';
                        $selSchoolID.append('<option value="' + optionx.schoolsListArray[sidx][i].ID + '" ' + selected + '>' + optionx.schoolsListArray[sidx][i].Name + '</option>');
                    }

                    $('#selStates').val(sidx + 1);
                    $('#selStates option[value=' + (sidx + 1) + ']').attr('selected', '');
                }
                else {
                    $selSchoolID.append('<option value="0">请选择所在大学</option>');

                }
            }
            else {
                $selSchoolID.append('<option value="0">请选择所在大学</option>');
            }
        }
    }
}();