'use strict'
$(document).ready(function() {


    $('.js-example-basic-single3').select2();
    $('.js-example-basic-single2').select2({
        placeholder: "Select Amenities",
    });


    $(".country").on('change', function(e) {
        $('.request-loader').addClass('show');
        let addedState = "state_id";
        let addedCity = "city_id";
        let addedArea = "area_id";
        let id = $(this).val();

        $.ajax({
            type: 'GET',
            url: stateUrl,
            data: {
                id: id,
            },
            success: function(data) {
                $('.' + addedArea).find('option').remove().end();
                if (data.states.length > 0) {
                    $('.state').show();
                    // $('.city').hide()
                    $('.' + addedState).find('option').remove();
                    $('.' + addedCity).find('option').remove();
                    $('.' + addedArea).find('option').remove();
                    $.each(data.states, function(key, value) {

                        $('.' + addedState).append($(
                            `<option></option>`
                        ).val(value
                            .id).html(value.state_content.name));
                    });

                    let firstStateId = data.states[0].id;


                    $.ajax({
                        type: 'GET',
                        url: cityUrl,
                        data: {
                            state_id: firstStateId,
                        },
                        success: function(data) {

                            if (data.cities.length > 0) {
                                $('.city').show();
                                $('.' + addedCity).find('option').remove()
                                    .end();
                              let firstCityId = data.cities[0].id;
                                $.each(data.cities, function(key, value) {
                                    $('.' + addedCity).append(
                                        $(
                                            `<option ></option>`
                                        ).val(value
                                            .id).html(value.city_content
                                            .name));
                                });

                                if (data.cities.length > 0) {

                                  $.ajax({
                                      type: 'GET',
                                      url: areaUrl,
                                      data: {
                                          city_id: firstCityId,
                                      },
                                      success: function(data) {

                                          if (data.areas.length > 0) {
                                              $('.area').show();
                                              $('.' + addedArea).find('option').remove().end();
                                              $.each(data.areas, function(key, value) {
                                                  $('.' + addedArea).append(
                                                      $(
                                                          `<option></option>`).val(value
                                                          .id).html(value.name));
                                              });
                                          } else {
                                              $('.' + addedArea).find('option').remove().end().append(
                                                  $(
                                                      `<option selected ></option>`).val('').html('No Area Found'));

                                          }
                                          $('.request-loader').removeClass('show');
                                      }
                                  });
                                }
                            }
                            $('.request-loader').removeClass('show');
                        }
                    });

                } else if (data.cities.length > 0) {
                    // $('.state').hide()
                    $('.city').show();
                    $('.' + addedCity).find('option').remove();
                    $.each(data.cities, function(key, value) {
                        $('.' + addedCity).append(
                            $(
                                `<option ></option>`
                            ).val(value
                                .id).html(value.city_content.name));
                    });
                } else if (data.areas.length > 0) {

                    // $('.state').hide()
                    // $('.city').hide();
                    $('.area').show();
                    $('.' + addedArea).find('option').remove().end();
                    $.each(data.areas, function(key, value) {

                        $('.' + addedArea).append(
                            $(
                                `<option></option>`).val(value
                                .id).html(value.name));
                    });
                }
                $('.request-loader').removeClass('show');
            }
        });
    });

    $(".vendor").on('change', function(e) {
        $('.request-loader').addClass('show');
        let id = $(this).val();

        $.ajax({
            type: 'GET',
            url: agentUrl,
            data: {
                vendor_id: id,
            },
            success: function(data) {
                if (data.agents.length > 0) {
                    $('.agent').removeClass('d-none');
                    $('.agent_id').html('<option selected value="">Please Select</option>');
                    $.each(data.agents, function(key, value) {

                        $('.agent_id').append($(
                            `<option></option>`
                        ).val(value
                            .id).html(value.username));
                    });
                } else {
                    $('.agent').addClass('d-none');
                }
                $('.request-loader').removeClass('show');
            }
        });
    });

});

function getCities(e) {
    console.log("city..");
    let $this = e.target;
    $('.request-loader').addClass('show');
    let addedCity = "city_id";
    let id = $($this).val();
    $.ajax({
        type: 'GET',
        url: cityUrl,
        data: {
            state_id: id,
        },
         success: function(data) {
            if (data.cities.length > 0) {
                $('.city').show();
                let $cityDropdown = $('.' + addedCity);
                $cityDropdown.find('option').remove(); // clear old options

                // 👉 add default option first
                $cityDropdown.append(
                    $('<option selected disabled></option>').val('').html('Select City')
                );

                // append dynamic cities
                $.each(data.cities, function(key, value) {
                    $cityDropdown.append(
                        $('<option></option>').val(value.id).html(value.city_content.name)
                    );
                });
            } else {
                $('.' + addedCity).find('option').remove().end().append(
                    $('<option selected disabled></option>').val('').html('No City Found')
                );
            }
            $('.request-loader').removeClass('show');
        }
    });
}

function getAreas(e) {
    let $this = e.target;
    $('.request-loader').addClass('show');
    let addedArea = "area_id";
    let id = $($this).val();
    $.ajax({
        type: 'GET',
        url: areaUrl,
        data: {
            city_id: id,
        },
        success: function(data) {

            if (data.areas.length > 0) {
                $('.area').show();
                $('.' + addedArea).find('option').remove().end();
                $.each(data.areas, function(key, value) {
                    $('.' + addedArea).append(
                        $(
                            `<option></option>`).val(value
                            .id).html(value.name));
                });
            } else {
                $('.' + addedArea).find('option').remove().end().append(
                    $(
                        `<option selected ></option>`).val('').html('No Area Found'));

            }
            $('.request-loader').removeClass('show');
        }
    });
}

function addUnitType()
{
  if (!modalOpen)
  {
    modalOpen = true;
    closemodal();
    $.get(unitAddUrl, function(res)
    {
      console.log("jjj");
      $('body').find('#modal-view-render').html(res.view);
      $('#add_unittype_modal').modal('show');
    });
  }
}

$(document).on('change', '.area_id', function() {
    let areaId = $(this).val();
    if (!areaId) return;

    // show loading state
    $('.country, .state_id, .city_id').each(function() {
        $(this)
            .prop('disabled', true)
            .html('<option>Loading...</option>')
            .closest('.form-group').show(); // ensure parent div is visible
    });

    $.ajax({
        type: 'GET',
        url: getLocationByAreaUrl,
        data: { area_id: areaId },
        success: function(res) {
            console.log(res     );
            if (res && res.country && res.state && res.city) {
                $('.country, .state_id, .city_id').fadeOut(150, function() {

                    // Update dropdowns with selected options
                    $('.country')
                        .html(`<option value="${res.country.id}" selected>${res.country.name}</option>`)
                        .prop('disabled', false) // keep enabled for dropdown look
                        .closest('.form-group').show();

                    $('.state_id')
                        .html(`<option value="${res.state.id}" selected>${res.state.name}</option>`)
                        .prop('disabled', false)
                        .closest('.form-group').show();

                    $('.city_id')
                        .html(`<option value="${res.city.id}" selected>${res.city.name}</option>`)
                        .prop('disabled', false)
                        .closest('.form-group').show();

                }).fadeIn(150);
            }
        },
        error: function() {
            alert('Failed to load location info.');
        }
    });
});