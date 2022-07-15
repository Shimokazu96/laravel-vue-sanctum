import { ref } from "vue";

export default function () {
    // 日付フォーマットを編集
    const customFormat = (value) => {
        let date = new Date(value);
        let y = date.getFullYear();
        let m = ("00" + (date.getMonth() + 1)).slice(-2);
        let d = ("00" + date.getDate()).slice(-2);
        return y + "/" + m + "/" + d;
    };

    //テーブル検索
    const filteredTable = (rows, search) => {
        let searchWord = search.trim() && search.toLowerCase();
        if (searchWord === "") return rows;
        return rows.filter(function (row) {
            return Object.keys(row).some(function (key) {
                return (
                    String(row[key]).toLowerCase().indexOf(searchWord) !== -1
                );
            });
        });
    };

    // テーブルソート
    const sort_key = ref(""); //ソートされる値
    const sort_asc = ref(true); // 昇順,降順判定
    //キーワード検索
    const searchKeyward = ref("");

    //ページネーション
    const per_page = ref(10);
    const current_page = ref(1);
    const last_page = ref("");

    //昇順/降順　並び替え
    const sortBy = (arg_sort_key, column) => {
        arg_sort_key === column
            ? (sort_asc.value = !sort_asc.value)
            : (sort_asc.value = true);
        sort_key.value = column;
    };
    const sortedTable = (arg_sort_key, arg_sort_asc, data) => {
        if (arg_sort_key != "") {
            let set = 1;
            arg_sort_asc ? (set = 1) : (set = -1);
            data.sort((a, b) => {
                if (a[arg_sort_key] < b[arg_sort_key]) return -1 * set;
                if (a[arg_sort_key] > b[arg_sort_key]) return 1 * set;
                return 0;
            });
            return data;
        } else {
            return data;
        }
    };

    // ソートの矢印の向き
    const addArrowClass = (arg_sort_key, column, arg_sort_asc) => {
        if (!column) {
            return;
        }
        return {
            asc: arg_sort_key === column && arg_sort_asc,
            desc: arg_sort_key === column && !arg_sort_asc,
        };
    };

    return {
        customFormat,
        filteredTable,
        sortBy,
        sortedTable,
        addArrowClass,
        sort_key,
        sort_asc,
        searchKeyward,
        per_page,
        current_page,
        last_page
    };
}
