function clearTracker() {
  
    var coinValueSelect = $('#tr-coinValue');
    var coinTypeSelect = $('#tr-coinType');
    var coinYearSelect = $('#tr-coinYear');
    var coinYearStartSelect = $('#tr-coinStartYear');
    var coinYearEndSelect = $('#tr-coinEndYear');
    var coinMintYearSelect = $('#tr-coinMintYear');
    var coinMintSelect = $('#tr-coinMint');

    var coinTypeDiv = $('#tr-coin-type');
    var coinYearDiv = $('#tr-coin-year');
    var coinYearRangeDiv = $('#tr-coin-year-range');
    var coinMintYearDiv = $('#tr-coin-mintyear');
    var coinMintDiv = $('#tr-coin-mint');

    coinTypeDiv.hide();
    coinYearDiv.hide();
    coinYearRangeDiv.hide();
    coinMintYearDiv.hide();
    coinMintDiv.hide();

    coinTypeSelect.empty();
    coinYearSelect.empty();
    coinYearStartSelect.empty();
    coinYearEndSelect.empty();
    coinMintYearSelect.empty();
    coinMintSelect.empty();
    
    coinValueSelect.val("");
    
    return false;
}



$(document).ready(function() {


  var coinValueSelect = $('#tr-coinValue');
  var coinTypeSelect = $('#tr-coinType');
  var coinYearSelect = $('#tr-coinYear');
  var coinYearStartSelect = $('#tr-coinStartYear');
  var coinYearEndSelect = $('#tr-coinEndYear');
  var coinMintYearSelect = $('#tr-coinMintYear');
  var coinMintSelect = $('#tr-coinMint');
  var gradeCategorySelect = $('#tr-grade-category');
  var gradeSelect = $('#tr-grade');
  
  var coinTypeDiv = $('#tr-coin-type');
  var coinYearDiv = $('#tr-coin-year');
  var coinYearRangeDiv = $('#tr-coin-year-range');
  var coinMintYearDiv = $('#tr-coin-mintyear');
  var coinMintDiv = $('#tr-coin-mint');
  var gradeCategoryDiv = $('#tr-gradeCategory');
  var gradeDiv = $('#tr-gradeRating');
  
  gradeCategorySelect.change(function() {
  
    gradeDiv.hide();
    gradeSelect.empty();
   
    var value = $("#tr-grade-category option:selected").val();

    if (value != '') {

      $.getJSON('/ajax/scalesForCategory.json', {'categoryId': value}, function(data) {
        var gradeitems = ['<option value="">-- Ratings --</option>'];
  
        $.each(data, function(key, value) {
              var srsid = data[key]['id'];
              var title = data[key]['title'];
              gradeitems.push('<option value="' + srsid + '">' + title + '</option>');            
		});
		    
		gradeSelect.html(gradeitems.join(''));
  
        gradeDiv.show();
  
      });  
      
    }
  });  
  
  // Get coin values
  $.getJSON('/ajax/coinValues.json', function(data) {
    var items = ['<option value="">-- Coin Type --</option>'];

    $.each(data, function(key, value) {
     var cvid = data[key]['id'];
     var name = data[key]['name'];
     items.push('<option value="' + cvid + '">' + name + '</option>');
    });

    coinValueSelect.html(items.join(''));
  
    coinValueSelect.change(function() {

	  // Hide sub selects      
      coinTypeDiv.hide();
      coinYearDiv.hide();
      coinYearRangeDiv.hide();
      coinMintYearDiv.hide();
      coinMintDiv.hide();

      coinTypeSelect.empty();
      coinYearSelect.empty();
      coinYearStartSelect.empty();
      coinYearEndSelect.empty();
      coinMintYearSelect.empty();
      coinMintSelect.empty();
      
      var value = $("#tr-coinValue option:selected").val();
      if (value != '') {
          $.getJSON('/ajax/coinsForValue.json', {'valueId': value}, function(data) {

            var coinitems = ['<option value="">-- Coin --</option>'];

            $.each(data, function(key, value) {
              var cid = data[key]['id'];
              var name = data[key]['name'];
              var dateRange = data[key]['dateRange'];
              coinitems.push('<option value="' + cid + '">' + name + ' (' + dateRange + ')</option>');
            
		    });
		    
		    coinTypeSelect.html(coinitems.join(''));
		    
		    coinTypeSelect.change(function() {

        	  // Hide sub selects      
              coinYearDiv.hide();
              coinYearRangeDiv.hide();
              coinMintYearDiv.hide();
              coinMintDiv.hide();

              coinYearSelect.empty();
              coinYearStartSelect.empty();
              coinYearEndSelect.empty();
              coinMintYearSelect.empty();
              coinMintSelect.empty();
              
              var value = $("#tr-coinType option:selected").val();
              if (value != '') {
                $.getJSON('/ajax/coinYears.json', {'typeId': value}, function(data) {

                  var yearitems = ['<option value="">-- Year --</option>'];
                  var yearstartitems = ['<option value="">-- Start --</option>'];
                  var yearenditems = ['<option value="">-- End --</option>'];

                  $.each(data, function(key, value) {
                    var cyid = data[key]['id'];
                    var year = data[key]['year'];
                    var yearInfo = data[key]['info'];
                    
                    var yearRangeOption = '<option value="' + cyid + '">' + year + '</option>';
                    var yearOption = '<option value="' + cyid + '">' + year;
                    if (yearInfo != '') {
                      yearOption += ' - ' + yearInfo;
                    }
                    yearOption += '</option>';
                    yearitems.push(yearOption);
                    yearstartitems.push(yearRangeOption);
                    yearenditems.push(yearRangeOption);
                  });
                  
                  coinYearSelect.html(yearitems.join(''));
                  coinYearStartSelect.html(yearstartitems.join(''));
                  coinYearEndSelect.html(yearenditems.join(''));
                  
                  coinYearStartSelect.change(function() {
                    var startValue = $("#tr-coinStartYear option:selected").val();
                    var endValue = $("#tr-coinEndYear option:selected").val();
                    var mint = $("#tr-coinMint option:selected").val();

                    if (startValue == '' && endValue == '' && mint == '') {
                      coinYearDiv.show();
                    }
                    else {
                      coinYearDiv.hide();
                      coinYearSelect.val("");

                    }
                  });

                  coinYearEndSelect.change(function() {
                 
                    var startValue = $("#tr-coinStartYear option:selected").val();
                    var endValue = $("#tr-coinEndYear option:selected").val();
                    var mint = $("#tr-coinMint option:selected").val();
                 
                    if (startValue == '' && endValue == '' && mint == '') {
                      coinYearDiv.show();
                    }
                    else {
                      coinYearDiv.hide();
                      coinYearSelect.val("");
                    }
                  });
                  
                  coinYearSelect.change(function() {
                    coinMintYearDiv.hide();                    
                    coinMintYearSelect.empty();
                    
                    var value = $("#tr-coinYear option:selected").val();
                    if (value != '') {
                    
                      coinMintDiv.hide();
                      coinYearRangeDiv.hide();

                      coinMintSelect.val("");
                      coinYearStartSelect.val("");
                      coinYearEndSelect.val("");
                    
                      $.getJSON('/ajax/coinYearMints.json', {'yearId': value}, function(data) {
                        var yearmintitems = ['<option value="">-- Mint --</option>'];
                        
                        $.each(data, function(key, value) {
                          var mcid = data[key]['id'];
                          var name = data[key]['name'];
                          var symbol = data[key]['symbol'];
                          var coinInfo = data[key]['info'];
                          
                          var coinOption = '<option value="' + mcid + '">' + name + ' (' + symbol + ')';
                          if (coinInfo != '') {
                            coinOption += ' - ' + coinInfo;
                          }
                          coinOption += '</option>';
                          yearmintitems.push(coinOption);
                          
                        });
                        
                        coinMintYearSelect.html(yearmintitems.join(''));
                      });
                      
                      coinMintYearDiv.show();
                    }
                    else {
                      coinMintDiv.show();
                      coinYearRangeDiv.show();
                    }
                          
                  });
                });

                $.getJSON('/ajax/mintsForCoin.json', {'typeId': value}, function(data) {

                  var mintitems = ['<option value="">-- Mint --</option>'];
                  
                  $.each(data, function(key, value) {
                    var mid = data[key]['id'];
                    var name = data[key]['name'];
                    var symbol = data[key]['symbol'];
                    
                    mintitems.push('<option value="' + mid + '">' + name + ' (' + symbol + ')</option>');
                  });
                  
                  coinMintSelect.html(mintitems.join(''));
                  
                  coinMintSelect.change(function() {
                    var value = $("#tr-coinMint option:selected").val();
                    if (value != '') {
                      coinYearDiv.hide();
                      coinMintYearDiv.hide();
                      
                      coinYearSelect.val("");
                      coinMintYearSelect.val("");
                    }
                    else {
                      var startValue = $("#tr-coinStartYear option:selected").val();
                      var endValue = $("#tr-coinEndYear option:selected").val();
                      var mint = $("#tr-coinMint option:selected").val();
                      
                      if (startValue == '' && endValue == '' && mint == '') {
                        coinYearDiv.show();
                      }
                    }
                  });
                });

                coinYearDiv.show();
                coinYearRangeDiv.show();
                coinMintDiv.show();
              }
              
            });
            
		    coinTypeDiv.show();
          });
      }
      
    });
      
  });

    
});