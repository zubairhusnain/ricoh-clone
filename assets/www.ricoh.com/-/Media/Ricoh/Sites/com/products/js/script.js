document.addEventListener("DOMContentLoaded", function () {
    let data = {};

    /*
     JSONの読み込み
    */
    fetch('/-/Media/Ricoh/Sites/com/products/js/data.json?2025')
        .then(response => {
            if (!response.ok) {
                throw new Error('データの読み込みに失敗しました。');
            }
            return response.json();
        })
        .then(json => {
            data = json;
            setSelectBox();
        })
        .catch(error => {
            console.error(error.message);
        });

    /*
     jsonからセレクトボックスを生成
    */
    function setSelectBox() {
        const DEP_TEXT = 'Region';
        const CTY_TEXT = 'Country/Area';

        const selectDep = document.querySelector('.js-select-dep');
        const selectCty = document.querySelector('.js-select-cty');
        const textDep = document.querySelector('.js-text-dep');
        const textCty = document.querySelector('.js-text-cty');
        const citySection = document.querySelector('.js-city');

        // Regionセレクトボックスの生成
        for (let dep in data) {
            const option = document.createElement('option');
            option.value = dep;
            option.textContent = dep;
            selectDep.appendChild(option);
        }

        // Region選択時のイベント設定
        selectDep.addEventListener('change', function () {
            const thisDep = this.value;

            textDep.textContent = thisDep || DEP_TEXT;
            textCty.textContent = CTY_TEXT;

            // Country/Areaセレクトボックスをリセット
            while (selectCty.options.length > 1) {
                selectCty.remove(1);
            }

            // Country/Areaの追加
            if (thisDep) {
                for (let country in data[thisDep]) {
                    const option = document.createElement('option');
                    option.value = country;
                    option.textContent = country;
                    selectCty.appendChild(option);
                }
                citySection.classList.add('is-show');
            } else {
                citySection.classList.remove('is-show');
            }
        });

        // Country/Area選択時のイベント設定
        selectCty.addEventListener('change', function () {
            const cty = this.value;
            textCty.textContent = cty || CTY_TEXT;
            if (cty) {
                showLinks();
            }
        });
    }

    /*
     演出
    */
    function showLinks() {
        const dep = document.querySelector('.js-select-dep').value;
        const cty = document.querySelector('.js-select-cty').value;

        const box = document.querySelector('.js-box');
        const listsWrapper = document.querySelector('.js-listsWrapper');
        const texts = document.querySelectorAll('.js-text');
        const subLinks = document.querySelectorAll('.js-subLink');
        const countryName = document.querySelector('.js-countryName');

        const setLinks = () => {
            const lists = data[dep][cty];

            lists.forEach((item, i) => {
                const link = document.createElement('a');
                link.href = item[1];
                link.target = '_blank';
                link.className = 'mainBox__body__item';
                link.innerHTML = `<p>${item[0]}</p>`;
                listsWrapper.appendChild(link);

                setTimeout(() => link.classList.add('is-show'), 120 * i);
            });

            listsWrapper.classList.add('is-show');
        };

        if (box.classList.contains('is-display')) {
            listsWrapper.classList.remove('is-show');
            setTimeout(() => {
                listsWrapper.innerHTML = ''; // リンクをリセット
                setLinks();
            }, 400);
        } else {
            setTimeout(setLinks, 200);
        }

        // ボックス表示
        box.classList.add('is-display');
        setTimeout(() => box.classList.add('is-visible'), 150);

        // 見出し部分の切り替え
        texts.forEach(text => text.classList.remove('is-show'));
        setTimeout(() => {
            countryName.textContent = cty;
            texts.forEach(text => text.classList.add('is-show'));
        }, 450);

        const linksElement = document.getElementById('links');
        console.log(linksElement.offsetTop);
        console.log(document.querySelector('[data-mv]').offsetHeight);

        // スクロール
        window.scrollTo({
            top: document.querySelector('[data-mv]').offsetHeight,
            behavior: 'smooth'
        });

        // 下部リンクの表示
        setTimeout(() => {
            subLinks.forEach((link, i) => {
                setTimeout(() => link.classList.add('is-show'), 200 * i);
            });
        }, 850);
    }
});
