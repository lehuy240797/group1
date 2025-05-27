@extends('layouts.app')

@section('title', 'Trang Chủ')

@section('content')

    <!-- Tiêu đề -->
    <div class="container mx-auto py-8">
        <p class="text-lg text-center mb-6 text-gray-600 font-medium italic">
            Khám phá những cảnh đẹp của Việt Nam
        </p>

    </div>

    <!-- Địa điểm nổi bật -->
    <div class="text-center mt-10">
        <h2 class="text-3xl font-bold mb-4">Địa điểm nổi bật</h2>
        <p class="text-xl text-red-600 mb-4 font-bold">Khám phá Huế qua góc nhìn 360</p>

        <div class="title text-2xl font-bold mt-16">An Hiên Garden</div>
        <div class="flex justify-center items-center gap-5 w-[95%] mx-auto">
            <!-- Hình ảnh bên trái -->
            <div class="w-1/2 h-[300px] flex items-center justify-center">
                <iframe class="w-full h-full rounded-lg" src="https://vr360.vietravel.net/vietnam/hue/an-hien-garden/"
                    frameborder="0"></iframe>
            </div>

            <!-- Nội dung bên phải -->
            <div class="w-1/2 h-[300px] overflow-y-auto p-5 bg-gray-100 border-2 border-gray-300 rounded-lg ">
                <div class="w-full">
                    <p class="text-gray-700 text-base sm:text-lg leading-relaxed">
                        Là một trong những nhà vườn nổi tiếng nhất ở Huế, An Hiên được xây dựng vào cuối thế kỷ thứ 19.
                        Ban đầu, ngôi nhà thuộc về công chúa thứ 18 của vua Dục Ðức. Năm 1920, An Hiên thuộc quyền quản lý
                        của ông Tùng Lễ.
                        Năm 1936, Nguyễn Đình Chi là chủ sở hữu của ngôi nhà được bán lại từ ông Tùng Lễ.
                        Năm 1940, Nguyễn Đình Chi qua đời và để lại khu nhà vườn cho bà Đào Thị Xuân Yến (vợ ông) quản lý.
                        Bà Đào Thị Xuân Yến cũng là chủ sở hữu dài nhất và là người đưa nhà vườn An Hiên ra phát triển mạnh
                        hơn cả.
                        Mặc dù đã trải qua hơn một thế kỷ tồn tại, nhưng không gian kiến ​​trúc của ngôi nhà vẫn giữ được
                        đặc tính cổ xưa của nó cho đến nay.

                        <br><br>

                        Khuôn viên nhà vườn An Hiên hiện nay có hình gần như vuông và có diện tích 4.608 m², mặt nhìn về
                        hướng Nam,
                        phía trước có sông Hương chảy ngang, bao gồm nhiều kiến trúc dân dụng lớn nhỏ, được xây dựng theo
                        lối kiến trúc truyền thống của Việt Nam và của xứ Huế.

                        <br><br>

                        Lối vào nhà vườn An Hiên là một cổng vòm nhỏ được xây dựng bằng gạch vôi vữa.
                        Dọc theo lối đi vào là hai dãy cây mận trắng đan tầng vào nhau cao vút và che bóng mát.
                        Rẽ trái và vượt qua chiếc bình phong cổ kính trang trí chữ Thọ, là một hồ nước hình chữ nhật được
                        bao phủ hoàn toàn bởi hoa súng, hoa sen và những cây cảnh xung quanh.

                        <br><br>

                        Kiến trúc chính của nhà vườn An Hiên là một ngôi nhà 3 gian 2 chái, nằm gần như ở trung tâm và được
                        điêu khắc tinh tế.
                        Toàn bộ cấu trúc khung trong nhà đều được làm bằng gỗ. Những hoa văn, họa tiết được chạm trổ tinh tế
                        bao quanh cột chính, hệ thống vì kèo của ngôi nhà.
                        Mái lợp ngói liệt nhiều lớp, bờ nóc hai bên đắp rồng chầu, ở giữa đỉnh mái có hình hoa sen.
                        Đặc biệt, các đồ nội thất cổ xưa trong ngôi nhà luôn gọn gàng và ngăn nắp.
                        Khám phá nhà vườn An Hiên chắc chắn sẽ là một trong những điều khó quên nhất khi trở về với xứ Huế
                        mộng mơ.
                    </p>
                </div>
            </div>
        </div>

        <div class="title text-2xl font-bold mt-16">Cầu Trường Tiền</div>
        <div class="flex justify-center items-center gap-5 w-[95%] mx-auto">
            <!-- Hình ảnh bên trái -->
            <div class="w-1/2 h-[300px] flex items-center justify-center">
                <iframe class="w-full h-full rounded-lg" src="https://vr360.vietravel.net/vietnam/hue/cau-truong-tien/"
                    frameborder="0"></iframe>
            </div>

            <!-- Nội dung bên phải -->
            <div class="w-1/2 h-[300px] overflow-y-auto p-5 bg-gray-100 border-2 border-gray-300 rounded-lg ">
                <div class="w-full">
                    <p class="text-gray-700 text-base sm:text-lg leading-relaxed">
                        Cầu Tràng Tiền (hay còn được gọi là Cầu Trường Tiền) bắc qua Sông Hương với những nhịp cầu cong cong
                        mềm mại,
                        uyển chuyển và là một trong những biểu tượng đặc trưng của cố đô Huế.

                        <br><br>

                        Cầu Tràng Tiền còn gắn liền với lịch sử hơn 100 năm và chứng kiến biết bao thăng trầm của lịch sử
                        dân tộc,
                        địa điểm du lịch Huế tham quan chứng nhân lịch sử.

                        <br><br>

                        Ngày nay, cầu được lắp đặt một hệ thống ánh sáng hiện đại, mỗi khi chiều buông lại toả sáng lung
                        linh rực rỡ nhiều màu sắc.
                    </p>
                </div>
            </div>
        </div>

        <div class="title text-2xl font-bold mt-16">Đại Nội Kinh Thành Huế</div>
        <div class="flex justify-center items-center gap-5 w-[95%] mx-auto">
            <!-- Hình ảnh bên trái -->
            <div class="w-1/2 h-[300px] flex items-center justify-center">
                <iframe class="w-full h-full rounded-lg"
                    src="https://vr360.vietravel.net/vietnam/hue/dai-noi-kinh-thanh-hue/" frameborder="0"></iframe>
            </div>

            <!-- Nội dung bên phải -->
            <div class="w-1/2 h-[300px] overflow-y-auto p-5 bg-gray-100 border-2 border-gray-300 rounded-lg ">
                <div class="w-full">
                    <p class="text-gray-700 text-base sm:text-lg leading-relaxed">
                        Đại Nội Huế là một trong những điểm du lịch nổi tiếng nhất tại Huế,
                        được UNESCO công nhận là Di sản văn hóa thế giới vào năm 1993.
                        Đây là nơi ở của các vua triều Nguyễn và là trung tâm chính trị, văn hóa của đất nước trong suốt 143
                        năm (1802 - 1945).

                        <br><br>

                        Đại Nội Huế được xây dựng từ năm 1805 đến năm 1833, với tổng diện tích khoảng 520 ha.
                        Khu vực này bao gồm Hoàng Thành, Tử Cấm Thành và nhiều công trình kiến trúc độc đáo khác như Ngọ
                        Môn, Điện Thái Hòa, Điện Cần Chánh, Thế Miếu, Hiển Lâm Các...

                        <br><br>

                        Đại Nội Huế không chỉ là một công trình kiến trúc độc đáo mà còn mang đậm giá trị văn hóa và lịch sử
                        của dân tộc Việt Nam.
                    </p>
                </div>
            </div>
        </div>

        <div class="title text-2xl font-bold mt-16">Chùa Thiên Mụ</div>
        <div class="flex justify-center items-center gap-5 w-[95%] mx-auto">
            <!-- Hình ảnh bên trái -->
            <div class="w-1/2 h-[300px] flex items-center justify-center">
                <iframe class="w-full h-full rounded-lg" src="https://vr360.vietravel.net/vietnam/hue/chua-thien-mu/"
                    frameborder="0"></iframe>
            </div>

            <!-- Nội dung bên phải -->
            <div class="w-1/2 h-[300px] overflow-y-auto p-5 bg-gray-100 border-2 border-gray-300 rounded-lg ">
                <div class="w-full">
                    <p class="text-gray-700 text-base sm:text-lg leading-relaxed">
                        Chùa Thiên Mụ (hay còn gọi là chùa Linh Mụ) là một trong những ngôi chùa cổ nhất và nổi tiếng nhất
                        tại Huế,
                        được xây dựng vào năm 1601 dưới triều đại Nguyễn. Chùa nằm trên đồi Hà Khê, bên bờ sông Hương, cách
                        trung tâm thành phố Huế khoảng 5 km về phía Tây Bắc.

                        <br><br>

                        Chùa Thiên Mụ nổi bật với tháp Phước Duyên cao 21 m, có 7 tầng, được xây dựng vào năm 1844.
                        Tháp được coi là biểu tượng của chùa và của thành phố Huế. Ngoài ra, chùa còn có nhiều công trình
                        kiến trúc độc đáo khác như điện Đại Hùng, điện Quan Âm, điện Thánh Mẫu...

                        <br><br>

                        Chùa Thiên Mụ không chỉ là một địa điểm du lịch nổi tiếng mà còn là nơi tổ chức nhiều lễ hội văn hóa
                        tâm linh quan trọng của người dân Huế.
                    </p>
                </div>
            </div>
        </div>

        <div class="title text-2xl font-bold mt-16">Khu phố Tây</div>
        <div class="flex justify-center items-center gap-5 w-[95%] mx-auto">
            <!-- Hình ảnh bên trái -->
            <div class="w-1/2 h-[300px] flex items-center justify-center">
                <iframe class="w-full h-full rounded-lg" src="https://vr360.vietravel.net/vietnam/hue/khu-pho-tay/"
                    frameborder="0"></iframe>
            </div>

            <!-- Nội dung bên phải -->
            <div class="w-1/2 h-[300px] overflow-y-auto p-5 bg-gray-100 border-2 border-gray-300 rounded-lg ">
                <div class="w-full">
                    <p class="text-gray-700 text-base sm:text-lg leading-relaxed">
                        Khu phố Tây Huế là một trong những điểm đến hấp dẫn nhất tại thành phố Huế,
                        nơi tập trung nhiều quán cà phê, nhà hàng, cửa hàng lưu niệm và các hoạt động giải trí thú vị.

                        <br><br>

                        Khu phố Tây nằm dọc bờ sông Hương, từ cầu Tràng Tiền đến cầu Phú Xuân.
                        Đây là nơi lý tưởng để du khách thưởng thức ẩm thực đặc sản của Huế, tham gia các hoạt động văn hóa
                        nghệ thuật và khám phá cuộc sống về đêm của thành phố.

                        <br><br>

                        Khu phố Tây Huế không chỉ là một địa điểm du lịch nổi tiếng mà còn là nơi giao lưu văn hóa giữa
                        người dân địa phương và du khách quốc tế.
                    </p>
                </div>
            </div>
        </div>

        <div class="title text-2xl font-bold mt-16">Lăng Cô</div>
        <div class="flex justify-center items-center gap-5 w-[95%] mx-auto">
            <!-- Hình ảnh bên trái -->
            <div class="w-1/2 h-[300px] flex items-center justify-center">
                <iframe class="w-full h-full rounded-lg" src="https://vr360.vietravel.net/vietnam/hue/lang-co/"
                    frameborder="0"></iframe>
            </div>

            <!-- Nội dung bên phải -->
            <div class="w-1/2 h-[300px] overflow-y-auto p-5 bg-gray-100 border-2 border-gray-300 rounded-lg ">
                <div class="w-full">
                    <p class="text-gray-700 text-base sm:text-lg leading-relaxed">
                        Lăng Cô là một trong những bãi biển đẹp nhất tại Huế,
                        nằm cách trung tâm thành phố khoảng 30 km về phía Bắc. Bãi biển Lăng Cô dài khoảng 10 km,
                        với cát trắng mịn và nước biển trong xanh, là nơi lý tưởng để tắm biển, lặn ngắm san hô và tham gia
                        các hoạt động thể thao dưới nước.

                        <br><br>

                        Lăng Cô còn nổi tiếng với cảnh quan thiên nhiên tuyệt đẹp,
                        bao gồm núi non hùng vĩ, rừng nguyên sinh và các đảo nhỏ xung quanh.
                        Đây là một trong những điểm đến không thể bỏ qua khi du lịch Huế.
                    </p>
                </div>
            </div>
        </div>

        <div class="title text-2xl font-bold mt-16">Lăng Khải Định</div>
        <div class="flex justify-center items-center gap-5 w-[95%] mx-auto">
            <!-- Hình ảnh bên trái -->
            <div class="w-1/2 h-[300px] flex items-center justify-center">
                <iframe class="w-full h-full rounded-lg" src="https://vr360.vietravel.net/vietnam/hue/lang-khai-dinh/"
                    frameborder="0"></iframe>
            </div>

            <!-- Nội dung bên phải -->
            <div class="w-1/2 h-[300px] overflow-y-auto p-5 bg-gray-100 border-2 border-gray-300 rounded-lg ">
                <div class="w-full">
                    <p class="text-gray-700 text-base sm:text-lg leading-relaxed">
                        Lăng Khải Định là một trong những lăng tẩm nổi tiếng nhất tại Huế,
                        được xây dựng vào năm 1920 dưới triều đại vua Khải Định. Lăng nằm trên đồi Châu Chữ, cách trung tâm
                        thành phố khoảng 10 km về phía Tây.

                        <br><br>

                        Lăng Khải Định nổi bật với kiến trúc độc đáo, kết hợp giữa phong cách kiến trúc phương Tây và phương
                        Đông.
                        Lăng có nhiều công trình kiến trúc đẹp mắt như điện Khải Thành, điện Thái Bình, điện Thái Hòa...

                        <br><br>

                        Lăng Khải Định không chỉ là một công trình kiến trúc độc đáo mà còn mang đậm giá trị văn hóa và lịch
                        sử của dân tộc Việt Nam.
                    </p>
                </div>
            </div>
        </div>

        <div class="title text-2xl font-bold mt-16">Lăng Tự Đức</div>
        <div class="flex justify-center items-center gap-5 w-[95%] mx-auto">
            <!-- Hình ảnh bên trái -->
            <div class="w-1/2 h-[300px] flex items-center justify-center">
                <iframe class="w-full h-full rounded-lg" src="https://vr360.vietravel.net/vietnam/hue/lang-tu-duc/"
                    frameborder="0"></iframe>
            </div>

            <!-- Nội dung bên phải -->
            <div class="w-1/2 h-[300px] overflow-y-auto p-5 bg-gray-100 border-2 border-gray-300 rounded-lg ">
                <div class="w-full">
                    <p class="text-gray-700 text-base sm:text-lg leading-relaxed">
                        Lăng Tự Đức là một trong những lăng tẩm nổi tiếng nhất tại Huế,
                        được xây dựng vào năm 1864 dưới triều đại vua Tự Đức. Lăng nằm trên đồi Thiên Thọ, cách trung tâm
                        thành phố khoảng 7 km về phía Tây.

                        <br><br>

                        Lăng Tự Đức nổi bật với kiến trúc độc đáo, kết hợp giữa phong cách kiến trúc phương Tây và phương
                        Đông.
                        Lăng có nhiều công trình kiến trúc đẹp mắt như điện Tự Đức, điện Thái Bình, điện Thái Hòa...

                        <br><br>

                        Lăng Tự Đức không chỉ là một công trình kiến trúc độc đáo mà còn mang đậm giá trị văn hóa và lịch sử
                        của dân tộc Việt Nam.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        // Swiper cho banner
        var bannerSwiper = new Swiper(".banner-swiper", {
            loop: true,
            autoplay: {
                delay: 2500
            },
            speed: 1000,
            effect: "fade",
        });

        // Swiper cho tour
        var tourSwiper = new Swiper(".tour-swiper", {
            loop: true,
            autoplay: {
                delay: 2000
            },
            speed: 1200,
            effect: "fade",
        });
    </script>

@endsection
