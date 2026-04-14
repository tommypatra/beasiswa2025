<script>
    const url = new URL(
        "https://api.sevimaplatform.com/siakadcloud/v1/user/login"
    );

    const headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-App-Key": "444C9593F2C6A864488F07087C995556",
        "X-Secret-Key": "7935D35A78429164F1D0E44F1B23B1FEB88FF8ADDB2F2AD05DF05D6CF16BB45F",
    };

    let body = {
        "email": "tommyirawan.patra@iainkendari.ac.id",
        "password": "12345678"
    };

    fetch(url, {
        method: "POST",
        headers,
        body: JSON.stringify(body),
    }).then(response => response.json());

</script>