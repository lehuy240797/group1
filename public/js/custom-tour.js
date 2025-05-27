document.addEventListener('DOMContentLoaded', function () {
    if (!window.priceData || !window.placesData || !window.flightPrices) {
        console.error("priceData, placesData, or flightPrices is not loaded. Check priceData.js or file loading.");
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Không thể tải dữ liệu giá. Vui lòng thử lại sau.',
            confirmButtonColor: '#d33'
        });
    }

    window.adjustTicket = function(ticketId, change) {
        let ticketInput = document.getElementById(ticketId);
        let newValue = parseInt(ticketInput.value) + change;
        if (newValue >= 0) {
            ticketInput.value = newValue;
            updateTotalCost();
        }
    };

    const solarLunar = window.solarLunar;
    console.log("solarLunar loaded:", solarLunar);
    console.log("priceData loaded:", window.priceData);
    console.log("flightPrices loaded:", window.flightPrices);
    console.log("placesData loaded:", window.placesData);

    const solarHolidays = [
        "2025-01-01", "2025-04-30", "2025-05-01", "2025-09-02",
        "2026-01-01", "2026-04-30", "2026-05-01", "2026-09-02",
        "2026-02-16", "2026-02-17", "2026-02-18", "2026-02-19", "2026-02-20", "2026-02-21", "2026-02-22"
    ];

    function isSolarHoliday(dateStr) {
        return solarHolidays.includes(dateStr);
    }

    function isHoliday(date) {
        const dateStr = date.toISOString().slice(0, 10);
        const isHolidayResult = isSolarHoliday(dateStr) || (typeof window.isInLunarHoliday === 'function' && window.isInLunarHoliday(date));
        console.log("isHoliday(" + dateStr + "):", isHolidayResult);
        return isHolidayResult;
    }

    document.getElementById("destination").addEventListener("change", function() {
        let destination = this.value;
        let placesContainer = document.getElementById("places-container");
        let hotelSelect = document.getElementById("hotel");
        let flightPriceDisplay = document.getElementById("flight-price");
        let flightPriceInput = document.getElementById("flight_price");

        console.log("Destination changed:", destination);
        console.log("priceData:", window.priceData);
        console.log("flightPrices:", window.flightPrices);

        flightPriceDisplay.textContent = (window.flightPrices[destination] || 0).toLocaleString() + " VND";
        flightPriceInput.value = window.flightPrices[destination] || 0;

        placesContainer.hidden = !destination;
        placesContainer.innerHTML = '';
        hotelSelect.innerHTML = '<option value="" disabled selected>Chọn khách sạn</option>';

        if (destination && window.priceData[destination]) {
            let label = document.createElement("label");
            label.textContent = "Chọn nơi bạn muốn đến:";
            label.classList.add("block", "font-semibold", "text-gray-700");
            placesContainer.appendChild(label);

            window.placesData[destination].forEach(place => {
                let checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.name = "places[]";
                checkbox.value = place;
                checkbox.classList.add("mr-2");
                checkbox.addEventListener("change", updateTotalCost);

                let label = document.createElement("label");
                label.appendChild(checkbox);
                label.append(` ${place} - ${(window.priceData[destination]["Địa điểm"][place] || 0).toLocaleString()} VND`);
                label.classList.add("block");

                placesContainer.appendChild(label);
            });
        }

        if (destination && window.priceData[destination]["Khách sạn"]) {
            Object.entries(window.priceData[destination]["Khách sạn"]).forEach(([hotelName, price]) => {
                let option = document.createElement("option");
                option.value = hotelName;
                option.textContent = `${hotelName} - ${price.toLocaleString()} VND`;
                hotelSelect.appendChild(option);
            });
        }

        updateTotalCost();
    });

    function updateTotalCost() {
        console.log("Updating total cost...");
        let destination = document.getElementById("destination").value;
        let startDate = document.getElementById("start_date").value;
        let endDate = document.getElementById("end_date").value;
        let adultTickets = parseInt(document.getElementById("adult_tickets").value) || 0;
        let childTickets = parseInt(document.getElementById("child_tickets").value) || 0;
        let hotel = document.getElementById("hotel").value.trim();

        console.log("Inputs:", { destination, startDate, endDate, adultTickets, childTickets, hotel });
        console.log("priceData[destination]:", window.priceData?.[destination]);
        console.log("Available hotels in priceData:", window.priceData?.[destination]?.["Khách sạn"]);

        // Kiểm tra nếu thiếu thông tin cần thiết
        if (!destination || !startDate || !endDate || !hotel) {
            console.log("Missing required fields. Total cost set to 0.");
            document.getElementById("total-cost").textContent = "0 VND";
            return;
        }

        let days = (new Date(endDate) > new Date(startDate)) ?
            Math.max(1, Math.ceil((new Date(endDate) - new Date(startDate)) / (1000 * 60 * 60 * 24))) : 0;
        let nights = days > 1 ? days - 1 : days;
        console.log("days:", days, "nights:", nights);

        if (days === 0) {
            console.log("Invalid date range. Total cost set to 0.");
            document.getElementById("total-cost").textContent = "0 VND";
            return;
        }

        let baseCost = window.priceData[destination]?.["Giá chung"] || 0;
        console.log("Base cost (Giá chung):", baseCost);

        let placeCost = 0;
        document.querySelectorAll("input[name='places[]']:checked").forEach(place => {
            let cost = window.priceData[destination]?.["Địa điểm"]?.[place.value] || 0;
            placeCost += cost;
            console.log("Place:", place.value, "Cost:", cost);
        });
        baseCost += placeCost;
        console.log("Base cost after places:", baseCost);

        let ticketCost = adultTickets > 0 ? (adultTickets * baseCost) + (childTickets * baseCost * 0.5) : 0;
        console.log("Ticket cost:", ticketCost);

        let totalPeople = adultTickets + childTickets;
        let numRooms = totalPeople > 0 ? Math.ceil(totalPeople / 2) : 0;
        let hotelPrice = window.priceData[destination]?.["Khách sạn"]?.[hotel] || 0;
        console.log("Hotel selected:", hotel, "Hotel price:", hotelPrice);
        if (hotelPrice === 0 && hotel) {
            console.warn("Hotel price is 0. Check priceData loading or hotel key match.");
        }
        let hotelCost = numRooms * hotelPrice * nights;
        console.log("Hotel cost:", hotelCost, { hotelPrice, numRooms, nights, totalPeople });

        let flightPrice = window.flightPrices[destination] || 0;
        let flightCost = (adultTickets + childTickets) * flightPrice;
        console.log("Flight cost:", flightCost);

        let totalCost = ticketCost + hotelCost + flightCost;
        console.log("Total cost before holiday:", totalCost);

        let isHolidayInRange = false;
        const start = new Date(startDate);
        const end = new Date(endDate);
        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            console.log("Checking date:", d.toISOString().slice(0, 10), "isHoliday:", isHoliday(d));
            if (isHoliday(d)) {
                isHolidayInRange = true;
                break;
            }
        }
        console.log("isHolidayInRange:", isHolidayInRange);
        if (isHolidayInRange) {
            totalCost *= 1.5;
            Swal.fire({
                icon: 'info',
                title: 'Ngày lễ',
                text: 'Khoảng thời gian bạn chọn bao gồm ngày lễ, chi phí sẽ tăng 50%.',
                confirmButtonColor: '#3085d6'
            });
        }

        console.log("Final total cost:", totalCost);
        document.getElementById("total-cost").textContent = `${totalCost.toLocaleString()} VND`;
        document.getElementById("priceData").value = JSON.stringify(window.priceData || {});
        document.getElementById("flight_price").value = window.flightPrices[destination] || 0;
    }

    document.getElementById("hotel").addEventListener("change", updateTotalCost);
    document.getElementById("start_date").addEventListener("change", updateTotalCost);
    document.getElementById("end_date").addEventListener("change", updateTotalCost);
    document.getElementById("adult_tickets").addEventListener("input", updateTotalCost);
    document.getElementById("child_tickets").addEventListener("input", updateTotalCost);

    document.getElementById('custom-tour-form').addEventListener('submit', function(event) {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        if (startDate && endDate && new Date(endDate) <= new Date(startDate)) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: 'Ngày về phải sau ngày đi.',
                confirmButtonColor: '#d33'
            });
        }
    });
});