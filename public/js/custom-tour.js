document.addEventListener('DOMContentLoaded', function () {
    window.adjustTicket = function(ticketId, change) {
        let ticketInput = document.getElementById(ticketId);
        let newValue = parseInt(ticketInput.value) + change;
        if (newValue >= 0) {
            ticketInput.value = newValue;
            updateTotalCost();
        }
    };

    const solarHolidays = [
        "2025-01-01", "2025-04-30", "2025-05-01", "2025-09-02",
        "2026-01-01", "2026-04-30", "2026-05-01", "2026-09-02",
        "2026-02-17", "2026-02-18", "2026-02-19", "2026-02-20", "2026-02-21", "2026-02-22", "2026-02-23"
    ];

    function isSolarHoliday(dateStr) {
        return solarHolidays.includes(dateStr);
    }

    function isHoliday(date) {
        const dateStr = date.toISOString().slice(0, 10);
        let isHolidayResult = isSolarHoliday(dateStr);
        try {
            isHolidayResult = isHolidayResult || isInLunarHoliday(date);
        } catch (e) {
            console.error("Error in isInLunarHoliday:", e);
        }
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
        console.log("priceData:", priceData);
        console.log("flightPrices:", flightPrices);

        flightPriceDisplay.textContent = (flightPrices[destination] || 0).toLocaleString() + " VND";
        flightPriceInput.value = flightPrices[destination] || 0;

       if (destination && placesData[destination]) {
            placesContainer.classList.remove("hidden");
            placesContainer.innerHTML = '<label class="block font-semibold text-gray-700">Chọn nơi bạn muốn đến:</label><div class="grid grid-cols-2 gap-3 bg-gray-50 p-3 rounded-lg"></div>';
            const checkboxContainer = placesContainer.querySelector("div");

            placesData[destination].forEach(place => {
                const div = document.createElement("div");
                div.className = "flex items-center bg-white border border-gray-200 rounded-md px-2 py-1 hover:bg-gray-100 transition-colors w-full";
                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.name = "places[]";
                checkbox.value = place;
                checkbox.id = `place_${place.replace(/\s+/g, "_")}`;
                checkbox.classList.add("mr-1");
                checkbox.addEventListener("change", function() {
                    console.log(`Checkbox ${place} changed, checked: ${this.checked}`);
                    updateTotalCost();
                });

                const label = document.createElement("label");
                label.htmlFor = `place_${place.replace(/\s+/g, "_")}`;
                label.textContent = `${place} (${(window.priceData[destination]?.["Địa điểm"]?.[place] || 0).toLocaleString()} VND)`;
                label.className = "text-sm truncate";
                div.appendChild(checkbox);
                div.appendChild(label);
                checkboxContainer.appendChild(div);
            });
        } else {
            placesContainer.classList.add("hidden");
            placesContainer.innerHTML = '<label class="block font-semibold text-gray-700">Chọn nơi bạn muốn đến:</label><div class="grid grid-cols-2 gap-3 bg-gray-50 p-3 rounded-lg"></div>';
        }

        // Cập nhật danh sách khách sạn
        hotelSelect.innerHTML = '<option value="" disabled selected>Chọn khách sạn</option>';
        if (destination && window.priceData[destination]?.["Khách sạn"]) {
            Object.entries(window.priceData[destination]["Khách sạn"]).forEach(([hotelName, price]) => {
                let option = document.createElement("option");
                option.value = hotelName;
                option.textContent = `${hotelName} - ${price.toLocaleString()} VND`;
                hotelSelect.appendChild(option);
            });
        }

        updateTotalCost();
    });

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
        } else {
            console.log("Form submitted with data:", {
                destination: document.getElementById('destination').value,
                hotel: document.getElementById('hotel').value,
                start_date: startDate,
                end_date: endDate,
                adult_tickets: document.getElementById('adult_tickets').value,
                child_tickets: document.getElementById('child_tickets').value,
                name: document.getElementById('name').value,
                phone: document.getElementById('phone').value,
                email: document.getElementById('email').value,
                price_data: document.getElementById('priceData').value,
                flight_price: document.getElementById('flight_price').value
            });
        }
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
    if (!destination) console.log("Warning: No destination selected");
    if (!startDate) console.log("Warning: No start date selected");
    if (!endDate) console.log("Warning: No end date selected");
    if (!hotel) console.log("Warning: No hotel selected");

        let numDays = (startDate && endDate && new Date(endDate) > new Date(startDate)) ?
            Math.ceil((new Date(endDate) - new Date(startDate)) / (1000 * 60 * 60 * 24)) : 0;
        console.log("numDays:", numDays);

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

        if (numDays > 1) {
            baseCost += baseCost * 0.1 * (numDays - 1);
            console.log("Base cost after day increase:", baseCost);
        }

        let ticketCost = adultTickets > 0 ? (adultTickets * baseCost) + (childTickets * baseCost * 0.5) : 0;
        console.log("Ticket cost:", ticketCost);

        let hotelPrice = hotel ? window.priceData[destination]?.["Khách sạn"]?.[hotel] || 0 : 0;
        let numRooms = childTickets > 0 ? Math.ceil(adultTickets) : Math.ceil(adultTickets / 2);
        let hotelCost = numRooms * hotelPrice * numDays;
        console.log("Hotel cost:", hotelCost, { hotelPrice, numRooms, numDays });

        let flightPrice = window.flightPrices[destination] || 0;
        let flightCost = (adultTickets + childTickets) * flightPrice;
        console.log("Flight cost:", flightCost, { flightPrice, totalTickets: adultTickets + childTickets });

        let totalCost = ticketCost + hotelCost + flightCost;
        console.log("Total cost before holiday:", totalCost);

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            let isHolidayInRange = false;
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
        }

        console.log("Final total cost:", totalCost);
        document.getElementById("total-cost").textContent = `${totalCost.toLocaleString()} VND`;
        document.getElementById("priceData").value = JSON.stringify(window.priceData);
        document.getElementById("flight_price").value = flightPrices[destination] || 0;
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
    updateTotalCost();
});