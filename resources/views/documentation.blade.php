{{-- Auto-generated from docs/ADMIN_GUIDE.md design. Regenerate via the same source if content changes. --}}
@verbatim
<title>CityStyleWears — Admin Guide</title>
<style>
  :root {
    --ink: #17181c;
    --paper: #faf9f6;
    --surface: #ffffff;
    --accent: #1346af;
    --accent-soft: #eef2fb;
    --line: #e6e3dc;
    --muted: #6c6f76;
    --code-bg: #f3f2ee;
    --shadow: 0 1px 2px rgba(23,24,28,0.04), 0 8px 24px rgba(23,24,28,0.06);
    --good-bg: #e7f5ec; --good-fg: #1e7a42;
    --warn-bg: #fdf1e0; --warn-fg: #9a5b12;
    --bad-bg: #fbe9e9; --bad-fg: #a3312f;
    color-scheme: light;
  }

  @media (prefers-color-scheme: dark) {
    :root {
      --ink: #eef0f3;
      --paper: #101115;
      --surface: #17181d;
      --accent: #7ea0f2;
      --accent-soft: #1b2740;
      --line: #2a2c33;
      --muted: #9a9da6;
      --code-bg: #1d1e24;
      --shadow: 0 1px 2px rgba(0,0,0,0.3), 0 8px 24px rgba(0,0,0,0.35);
      --good-bg: #12281c; --good-fg: #6bd394;
      --warn-bg: #2c2210; --warn-fg: #e3ab5c;
      --bad-bg: #2c1616; --bad-fg: #ec8b89;
      color-scheme: dark;
    }
  }
  :root[data-theme="dark"] {
    --ink: #eef0f3; --paper: #101115; --surface: #17181d; --accent: #7ea0f2;
    --accent-soft: #1b2740; --line: #2a2c33; --muted: #9a9da6; --code-bg: #1d1e24;
    --shadow: 0 1px 2px rgba(0,0,0,0.3), 0 8px 24px rgba(0,0,0,0.35);
    --good-bg: #12281c; --good-fg: #6bd394; --warn-bg: #2c2210; --warn-fg: #e3ab5c;
    --bad-bg: #2c1616; --bad-fg: #ec8b89;
    color-scheme: dark;
  }
  :root[data-theme="light"] {
    --ink: #17181c; --paper: #faf9f6; --surface: #ffffff; --accent: #1346af;
    --accent-soft: #eef2fb; --line: #e6e3dc; --muted: #6c6f76; --code-bg: #f3f2ee;
    --shadow: 0 1px 2px rgba(23,24,28,0.04), 0 8px 24px rgba(23,24,28,0.06);
    --good-bg: #e7f5ec; --good-fg: #1e7a42; --warn-bg: #fdf1e0; --warn-fg: #9a5b12;
    --bad-bg: #fbe9e9; --bad-fg: #a3312f;
    color-scheme: light;
  }

  * { box-sizing: border-box; }
  html, body {
    margin: 0; padding: 0; background: var(--paper); color: var(--ink);
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
  }

  a { color: var(--accent); }
  a:focus-visible, button:focus-visible, input:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

  .eyebrow {
    text-transform: uppercase; letter-spacing: 0.14em; font-size: 11px; font-weight: 700;
    color: var(--muted);
  }

  /* Sidebar — echoes the admin panel's own black nav rail. Truly fixed so it never scrolls with the page. */
  .toc {
    position: fixed; top: 0; left: 0; bottom: 0; width: 248px; flex-shrink: 0;
    background: var(--ink); color: #d7d9de; overflow-y: auto;
    padding: 28px 20px 40px; z-index: 30;
  }
  @media (prefers-color-scheme: dark) { .toc { background: #0a0b0d; } }
  :root[data-theme="dark"] .toc { background: #0a0b0d; }
  :root[data-theme="light"] .toc { background: var(--ink); color: #d7d9de; }

  .back-to-admin {
    display: flex; align-items: center; gap: 7px; text-decoration: none;
    color: #fff; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
    font-size: 12.5px; font-weight: 600; padding: 9px 12px; border-radius: 7px; margin-bottom: 20px;
  }
  .back-to-admin:hover { background: rgba(255,255,255,0.14); }
  .back-to-admin .arrow { font-size: 14px; line-height: 1; }

  .menu-toggle {
    display: none; position: fixed; top: 14px; left: 14px; z-index: 40;
    width: 40px; height: 40px; border-radius: 8px; border: 1px solid var(--line);
    background: var(--surface); color: var(--ink); box-shadow: var(--shadow);
    align-items: center; justify-content: center; font-size: 16px; cursor: pointer;
  }
  .toc-scrim { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 25; }

  .toc-brand { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
  .toc-brand .mark {
    width: 30px; height: 30px; border-radius: 8px; background: #fff; color: #000;
    display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;
    flex-shrink: 0;
  }
  .toc-brand .name { font-weight: 700; font-size: 14.5px; color: #fff; line-height: 1.25; }
  .toc-brand .name small { display: block; font-weight: 500; font-size: 10.5px; color: #93969e; letter-spacing: 0.06em; text-transform: uppercase; margin-top: 2px; }

  .toc nav { display: flex; flex-direction: column; }
  .toc nav a {
    color: #b7b9c2; text-decoration: none; font-size: 13.5px; padding: 7px 10px; border-radius: 6px;
    display: flex; align-items: center; gap: 9px;
  }
  .toc nav a:hover { background: rgba(255,255,255,0.06); color: #fff; }
  .toc nav a.active { background: rgba(126,160,242,0.16); color: #fff; }
  .toc nav a .num { font-variant-numeric: tabular-nums; color: #6d707a; font-size: 11.5px; width: 16px; }

  .toc-foot { margin-top: 28px; padding-top: 18px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 11.5px; color: #74777f; line-height: 1.6; }

  /* Main content */
  main { margin-left: 248px; min-width: 0; }
  .wrap { max-width: 760px; margin: 0 auto; padding: 64px 40px 120px; }

  .cover h1 {
    font-size: 34px; line-height: 1.12; letter-spacing: -0.01em; margin: 10px 0 12px; text-wrap: balance;
    font-weight: 800;
  }
  .cover p.lede { font-size: 16.5px; line-height: 1.65; color: var(--muted); max-width: 58ch; margin: 0 0 28px; }
  .cover .meta-row { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 40px; }
  .pill {
    font-size: 11.5px; font-weight: 600; padding: 5px 11px; border-radius: 999px;
    background: var(--accent-soft); color: var(--accent); letter-spacing: 0.02em;
  }

  .toc-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 8px 20px; border-top: 1px solid var(--line);
    padding-top: 24px; margin-bottom: 8px;
  }
  .toc-grid a {
    display: flex; align-items: baseline; gap: 10px; padding: 9px 4px; text-decoration: none; color: var(--ink);
    border-bottom: 1px solid transparent; font-size: 14.5px;
  }
  .toc-grid a:hover { color: var(--accent); }
  .toc-grid a .n { color: var(--muted); font-variant-numeric: tabular-nums; font-size: 12.5px; width: 18px; flex-shrink: 0; }

  section.card {
    scroll-margin-top: 24px;
    border-top: 1px solid var(--line);
    padding: 56px 0 8px;
  }
  section.card:first-of-type { border-top: none; }

  .kicker { display: flex; align-items: center; gap: 10px; margin-bottom: 6px; }
  .kicker .idx {
    font-variant-numeric: tabular-nums; font-size: 12.5px; color: var(--muted); font-weight: 700;
  }
  h2 { font-size: 24px; margin: 0 0 14px; letter-spacing: -0.01em; text-wrap: balance; font-weight: 800; }
  h3 { font-size: 16px; margin: 32px 0 10px; font-weight: 700; }
  p { font-size: 15px; line-height: 1.7; color: var(--ink); }
  p.sub { color: var(--muted); font-size: 14.5px; margin-top: -6px; margin-bottom: 20px; }
  ul.plain, ol.steps { padding-left: 0; margin: 14px 0; list-style: none; }

  ol.steps { counter-reset: step; display: flex; flex-direction: column; gap: 14px; }
  ol.steps li {
    counter-increment: step; display: grid; grid-template-columns: 26px 1fr; gap: 12px; font-size: 14.5px;
    line-height: 1.65;
  }
  ol.steps li::before {
    content: counter(step); font-weight: 700; color: var(--accent); background: var(--accent-soft);
    width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 12.5px; flex-shrink: 0; font-variant-numeric: tabular-nums;
  }

  ul.plain { display: flex; flex-direction: column; gap: 10px; }
  ul.plain li { font-size: 14.5px; line-height: 1.6; padding-left: 18px; position: relative; }
  ul.plain li::before { content: "–"; position: absolute; left: 0; color: var(--muted); }

  .field-table { width: 100%; border-collapse: collapse; margin: 16px 0 8px; font-size: 13.5px; }
  .field-table th, .field-table td { text-align: left; padding: 9px 12px; border-bottom: 1px solid var(--line); vertical-align: top; }
  .field-table th { color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; font-size: 11px; }
  .field-table td.field-name { font-weight: 600; white-space: nowrap; }
  .field-table-wrap { overflow-x: auto; }

  .shot {
    margin: 22px 0 28px; border: 1px solid var(--line); border-radius: 10px; overflow: hidden;
    box-shadow: var(--shadow); background: var(--surface);
  }
  .shot img { display: block; width: 100%; height: auto; }
  .shot figcaption {
    font-size: 12.5px; color: var(--muted); padding: 10px 14px; border-top: 1px solid var(--line);
    background: var(--surface);
  }
  figure { margin: 0; }

  .callout {
    border-radius: 10px; padding: 14px 16px; font-size: 14px; line-height: 1.6; margin: 18px 0;
    display: flex; gap: 10px;
  }
  .callout .icon { flex-shrink: 0; font-weight: 800; width: 20px; text-align: center; }
  .callout.tip { background: var(--accent-soft); color: var(--ink); }
  .callout.tip .icon { color: var(--accent); }
  .callout.warn { background: var(--warn-bg); color: var(--ink); }
  .callout.warn .icon { color: var(--warn-fg); }

  .status-row { display: flex; flex-wrap: wrap; gap: 8px; margin: 14px 0 20px; }
  .status-chip {
    font-size: 12px; font-weight: 700; padding: 5px 11px; border-radius: 999px; letter-spacing: 0.02em;
  }
  .s-pending { background: var(--warn-bg); color: var(--warn-fg); }
  .s-paid, .s-delivered, .s-processing, .s-shipped { background: var(--good-bg); color: var(--good-fg); }
  .s-cancelled, .s-refunded, .s-failed { background: var(--bad-bg); color: var(--bad-fg); }

  code, .code-inline {
    font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: 0.9em;
    background: var(--code-bg); padding: 1.5px 6px; border-radius: 5px;
  }

  footer.end { border-top: 1px solid var(--line); margin-top: 60px; padding-top: 24px; font-size: 13px; color: var(--muted); }

  @media (max-width: 880px) {
    .toc { left: -260px; transition: left .2s ease; box-shadow: 4px 0 24px rgba(0,0,0,0.2); }
    .toc.open { left: 0; }
    .menu-toggle { display: flex; }
    .toc-scrim.open { display: block; }
    main { margin-left: 0; }
    .wrap { padding: 76px 22px 100px; }
    .toc-grid { grid-template-columns: 1fr; }
  }
</style>

<button class="menu-toggle" id="menuToggle" aria-label="Open menu" aria-expanded="false" aria-controls="toc">☰</button>
<div class="toc-scrim" id="tocScrim"></div>

<div class="toc" id="toc">
  <div class="toc-brand">
    <div class="mark">CS</div>
    <div class="name">CityStyleWears<small>Admin Guide</small></div>
  </div>
  <a class="back-to-admin" href="/admin"><span class="arrow">←</span> Back to Admin Panel</a>
  <nav>
    <a href="#cover"><span class="num">–</span> Start here</a>
    <a href="#login"><span class="num">01</span> Logging in</a>
    <a href="#dashboard"><span class="num">02</span> Dashboard</a>
    <a href="#products"><span class="num">03</span> Products</a>
    <a href="#categories"><span class="num">04</span> Categories</a>
    <a href="#orders"><span class="num">05</span> Orders</a>
    <a href="#customers"><span class="num">06</span> Customers</a>
    <a href="#coupons"><span class="num">07</span> Coupons</a>
    <a href="#slides"><span class="num">08</span> Homepage Slides</a>
    <a href="#faqs"><span class="num">09</span> FAQs</a>
    <a href="#settings"><span class="num">10</span> Site Settings</a>
    <a href="#reference"><span class="num">11</span> Quick Reference</a>
  </nav>
  <div class="toc-foot">Covers the admin panel as of the current build. Ask your developer if a screen looks different from this guide.</div>
</div>

<main>
  <div class="wrap">

    <div class="cover" id="cover">
      <p class="eyebrow">Internal Reference</p>
      <h1>Running the CityStyleWears admin panel</h1>
      <p class="lede">Everything the store team needs to manage products, orders, customers, and the storefront itself — no developer required. Each section below matches a link in the sidebar and shows the actual screens you'll be working in.</p>
      <div class="meta-row">
        <span class="pill">11 sections</span>
        <span class="pill">Screenshots included</span>
        <span class="pill">No coding required</span>
      </div>

      <div class="toc-grid">
        <a href="#login"><span class="n">01</span> Logging in</a>
        <a href="#dashboard"><span class="n">02</span> Dashboard</a>
        <a href="#products"><span class="n">03</span> Products</a>
        <a href="#categories"><span class="n">04</span> Categories</a>
        <a href="#orders"><span class="n">05</span> Orders</a>
        <a href="#customers"><span class="n">06</span> Customers</a>
        <a href="#coupons"><span class="n">07</span> Coupons</a>
        <a href="#slides"><span class="n">08</span> Homepage Slides</a>
        <a href="#faqs"><span class="n">09</span> FAQs</a>
        <a href="#settings"><span class="n">10</span> Site Settings</a>
        <a href="#reference"><span class="n">11</span> Quick Reference</a>
      </div>
    </div>

    <section class="card" id="login">
      <div class="kicker"><span class="idx">01</span></div>
      <h2>Logging in</h2>
      <p class="sub">The admin panel lives at <span class="code-inline">/login</span> on the store's domain.</p>

      <ol class="steps">
        <li>Go to <span class="code-inline">citystylewears.com/login</span>.</li>
        <li>Enter your admin email address and password.</li>
        <li>You'll land straight on the Dashboard. If you're ever unsure where you are, the sidebar on the left always shows which section you're in.</li>
      </ol>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/00-login.png" alt="CityStyleWears login screen">
          <figcaption>The login screen — same one your customers see, but your account has the admin role attached to it.</figcaption>
        </figure>
      </div>

      <div class="callout warn">
        <span class="icon">!</span>
        <div>Forgotten your password? Use "Forgot your password?" on the login screen — a reset link is emailed to you. If email isn't set up yet on the store (see <a href="#settings">Site Settings → Mail</a>), ask your developer to reset it directly.</div>
      </div>
    </section>

    <section class="card" id="dashboard">
      <div class="kicker"><span class="idx">02</span></div>
      <h2>Dashboard</h2>
      <p class="sub">Your landing page after login — a quick pulse-check on the store.</p>

      <p>Four numbers up top: total <strong>Products</strong>, total <strong>Orders</strong>, total <strong>Customers</strong>, and <strong>Revenue</strong> (the sum of every order marked <em>Paid</em> or further along). Below that, the five most recent orders, whatever their status.</p>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/01-dashboard.png" alt="Admin dashboard showing product, order, customer counts and revenue">
          <figcaption>Dashboard — the four count cards and the Recent Orders list.</figcaption>
        </figure>
      </div>

      <div class="callout tip">
        <span class="icon">i</span>
        <div>Revenue only counts orders that have actually been paid for — an order sitting at "Pending" or "Failed" won't add to that number yet.</div>
      </div>
    </section>

    <section class="card" id="products">
      <div class="kicker"><span class="idx">03</span></div>
      <h2>Products</h2>
      <p class="sub">Everything for sale in the store — names, prices, stock, photos, sizes, and colors.</p>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/02-products-list.png" alt="Products list showing name, category, price, stock and status">
          <figcaption>The Products list. Search by name at the top; Edit or Delete any row on the right.</figcaption>
        </figure>
      </div>

      <h3>Adding a new product</h3>
      <ol class="steps">
        <li>Click <strong>Add Product</strong> in the top-right corner.</li>
        <li>Fill in the <strong>Name</strong>, an optional <strong>SKU</strong> (your own internal reference code), and pick a <strong>Category</strong>.</li>
        <li>Set the <strong>Price</strong>. <strong>Compare-at Price</strong> is optional — fill it in only if you want to show a struck-through "was" price next to the real one.</li>
        <li>Set <strong>Stock Qty</strong> — this is only used if the product has no sizes/colors (see below). Once it comes with variants, stock is tracked per size/color instead.</li>
        <li>Choose a <strong>Status</strong>: <span class="code-inline">Draft</span> keeps it hidden from customers while you're still preparing it; <span class="code-inline">Active</span> makes it live; <span class="code-inline">Sold Out</span> and <span class="code-inline">Archived</span> both hide it from the main shop but keep the record.</li>
        <li>Tick <strong>Featured</strong> and/or <strong>Bestseller</strong> if this product should appear in those homepage sections.</li>
        <li>Write a <strong>Description</strong> — this shows on the product's page.</li>
        <li>If the product comes in more than one size, tick the sizes it's available in under <strong>Sizes &amp; Colors</strong>. If it also comes in specific colors, type them into the colors box (comma separated) and click <strong>+ Add Colors</strong>.</li>
        <li>Under <strong>Add Images</strong>, choose one or more photos. The first one becomes the product's main photo.</li>
        <li>Click <strong>Save</strong>.</li>
      </ol>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/03-products-form.png" alt="Add product form with name, category, price, sizes and image upload">
          <figcaption>The Add/Edit Product form, including size selection and image upload.</figcaption>
        </figure>
      </div>

      <div class="callout tip">
        <span class="icon">i</span>
        <div>Leave Sizes &amp; Colors empty for something that's simply one size fits all, like a cap or a tote bag — the plain Stock Qty field is all it needs.</div>
      </div>
    </section>

    <section class="card" id="categories">
      <div class="kicker"><span class="idx">04</span></div>
      <h2>Categories</h2>
      <p class="sub">The groupings customers browse by — T-Shirts, Outerwear, Tracksuits, and so on.</p>

      <ol class="steps">
        <li>Click <strong>Add Category</strong>.</li>
        <li>Give it a <strong>Name</strong>. If it's a sub-group of an existing category, choose that as its <strong>Parent Category</strong> — otherwise leave it as "None (Top Level)".</li>
        <li><strong>Sort Order</strong> controls left-to-right / top-to-bottom position — lower numbers appear first.</li>
        <li>Untick <strong>Enabled</strong> to hide a category from the shop temporarily without deleting it.</li>
      </ol>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/04-categories.png" alt="Categories list with parent, enabled status and edit/delete actions">
          <figcaption>Categories list — sub-categories are indented with a dash.</figcaption>
        </figure>
      </div>
    </section>

    <section class="card" id="orders">
      <div class="kicker"><span class="idx">05</span></div>
      <h2>Orders</h2>
      <p class="sub">Every order placed on the store, with search, filters, invoices, and status updates.</p>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/05-orders-list.png" alt="Orders list with search, status and date filters">
          <figcaption>Orders list with Search, Status, and date range filters, plus Export CSV in the top-right.</figcaption>
        </figure>
      </div>

      <h3>Finding an order</h3>
      <p>Type into <strong>Search</strong> to match against an order number, customer name, email, phone, or payment reference. Combine it with the <strong>Status</strong> dropdown or a <strong>From/To</strong> date range to narrow things down further, or click <strong>Reset</strong> to clear all filters.</p>

      <h3>Viewing an order</h3>
      <p>Click <strong>View</strong> on any row to expand its full detail inline: shipping address, the customer's own address if it differs, payment reference, coupon used (if any), a line-by-line item list, and the subtotal / discount / shipping / total breakdown.</p>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/06-orders-detail.png" alt="Expanded order detail showing address, payment reference and item breakdown">
          <figcaption>An order expanded — everything about that sale in one place.</figcaption>
        </figure>
      </div>

      <h3>Updating order status</h3>
      <p>Use the status dropdown directly on an order's row — it saves the moment you pick a new value, no extra "save" click needed.</p>
      <div class="status-row">
        <span class="status-chip s-pending">Pending</span>
        <span class="status-chip s-paid">Paid</span>
        <span class="status-chip s-processing">Processing</span>
        <span class="status-chip s-shipped">Shipped</span>
        <span class="status-chip s-delivered">Delivered</span>
        <span class="status-chip s-cancelled">Cancelled</span>
        <span class="status-chip s-refunded">Refunded</span>
        <span class="status-chip s-failed">Failed</span>
      </div>
      <p class="sub" style="margin-top:-8px;">See <a href="#reference">Quick Reference</a> for what each status means and when to use it.</p>

      <h3>Downloading an invoice</h3>
      <p>Click <strong>Download</strong> on any order to get a clean, printable PDF invoice — useful for your own records or if a customer asks for a receipt.</p>

      <h3>Exporting to a spreadsheet</h3>
      <p>Click <strong>Export CSV</strong> in the top-right to download every order that matches your current search/filters as a spreadsheet file, ready to open in Excel or Google Sheets.</p>
    </section>

    <section class="card" id="customers">
      <div class="kicker"><span class="idx">06</span></div>
      <h2>Customers</h2>
      <p class="sub">Everyone who's registered an account, and what they've spent.</p>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/07-customers-list.png" alt="Customers list with registration date, order count and total spent">
          <figcaption>Customers list, sorted by registration date by default.</figcaption>
        </figure>
      </div>

      <p>Search by name, email, or phone at the top. Click any column heading (like <strong>Registered</strong>) to sort by it — click again to flip the order.</p>

      <p><strong>Total Spent</strong> only counts orders that reached Paid, Processing, Shipped, or Delivered — a failed or still-pending order won't inflate a customer's number.</p>

      <h3>Seeing a customer's order history</h3>
      <p>Click <strong>View Orders</strong> next to any customer to expand their full order history right there in the list — no need to jump over to the Orders page and search for them.</p>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/08-customers-detail.png" alt="Expanded customer detail showing their individual order history">
          <figcaption>A customer's row expanded to show every order they've placed.</figcaption>
        </figure>
      </div>
    </section>

    <section class="card" id="coupons">
      <div class="kicker"><span class="idx">07</span></div>
      <h2>Coupons</h2>
      <p class="sub">Discount codes customers can apply at checkout.</p>

      <ol class="steps">
        <li>Click <strong>Add Coupon</strong>.</li>
        <li>Set the <strong>Code</strong> customers will type in (e.g. <span class="code-inline">WELCOME10</span>).</li>
        <li>Choose <strong>Type</strong> — Percent Off (e.g. 10%) or Fixed Amount Off (e.g. ₦2,000).</li>
        <li>Set the <strong>Value</strong> to match — a number like <span class="code-inline">10</span> for 10%, or <span class="code-inline">2000</span> for a flat ₦2,000.</li>
        <li><strong>Min Order Amount</strong> stops the coupon applying below a certain cart total — leave blank for no minimum.</li>
        <li><strong>Usage Limit</strong> caps how many times the code can be used in total across all customers — leave blank for unlimited.</li>
        <li><strong>Expires At</strong> is optional — after that date the code stops working automatically.</li>
        <li>Untick <strong>Enabled</strong> at any time to switch a code off without deleting it.</li>
      </ol>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/09-coupons.png" alt="Coupons list showing code, discount, usage count and expiry">
          <figcaption>Coupons list — the "Used" column tracks redemptions automatically as customers check out.</figcaption>
        </figure>
      </div>
    </section>

    <section class="card" id="slides">
      <div class="kicker"><span class="idx">08</span></div>
      <h2>Homepage Slides</h2>
      <p class="sub">The two rotating photo sections on the homepage — the big banner at the very top, and the lookbook strip further down.</p>

      <p>There are two separate slideshows, switched between with the tabs at the top of this screen:</p>
      <ul class="plain">
        <li><strong>Hero Slideshow</strong> — the large banner at the very top of the homepage. Each slide can carry a button (like "Shop New Arrivals") linking anywhere you choose.</li>
        <li><strong>Campaign Lookbook</strong> — the rotating photo strip shown further down the homepage, just after "Explore All Collection". No button here, just the photo and its heading.</li>
      </ul>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/10-slides-hero.png" alt="Hero Slideshow tab showing four slides with edit and delete actions">
          <figcaption>Hero Slideshow tab, with its four current slides in display order.</figcaption>
        </figure>
      </div>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/11-slides-campaign.png" alt="Campaign Lookbook tab showing its own set of slides">
          <figcaption>Switching to the Campaign Lookbook tab shows a completely separate list of slides.</figcaption>
        </figure>
      </div>

      <h3>Adding or editing a slide</h3>
      <ol class="steps">
        <li>Make sure you're on the correct tab first (Hero or Campaign) — new slides are added to whichever tab you're viewing.</li>
        <li>Click <strong>Add Slide</strong> and choose an <strong>Image</strong> to upload.</li>
        <li>Add a <strong>Heading</strong> if you want text overlaid on the photo.</li>
        <li>On the Hero tab only, optionally set <strong>Button Text</strong> and <strong>Button Link</strong> (a shop URL, a specific product, or any external link).</li>
        <li><strong>Sort Order</strong> decides the sequence slides rotate in — lower numbers show first.</li>
        <li>Untick <strong>Enabled</strong> to hide a slide from the live site without deleting it — handy for prepping a slide ahead of time.</li>
      </ol>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/12-slides-form.png" alt="Slide form with image upload, heading, button text and link fields">
          <figcaption>The slide form — button fields only appear while on the Hero Slideshow tab.</figcaption>
        </figure>
      </div>

      <div class="callout tip">
        <span class="icon">i</span>
        <div>For the best crop on the homepage, use photos with your subject roughly centered — both slideshows crop tightly on smaller screens.</div>
      </div>
    </section>

    <section class="card" id="faqs">
      <div class="kicker"><span class="idx">09</span></div>
      <h2>FAQs</h2>
      <p class="sub">The question-and-answer list on the store's FAQs page.</p>

      <ol class="steps">
        <li>Click <strong>Add FAQ</strong>.</li>
        <li>Type the <strong>Question</strong> as customers would ask it, and the <strong>Answer</strong> underneath.</li>
        <li><strong>Sort Order</strong> controls where it falls in the list — lower numbers show first.</li>
        <li>Untick <strong>Enabled</strong> to hide a question from the live FAQs page without deleting it.</li>
      </ol>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/13-faqs.png" alt="FAQs list showing question, sort order and enabled status">
          <figcaption>The FAQs list, in the order they'll appear on the site.</figcaption>
        </figure>
      </div>
    </section>

    <section class="card" id="settings">
      <div class="kicker"><span class="idx">10</span></div>
      <h2>Site Settings</h2>
      <p class="sub">One page covering branding, homepage text, payments, outgoing email, and live chat.</p>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/14-settings-top.png" alt="Site settings top section with site name, tagline, logos, promo bar and contact info">
          <figcaption>General &amp; branding — site name, tagline, logo uploads, promo bar text, and contact details.</figcaption>
        </figure>
      </div>

      <div class="field-table-wrap">
        <table class="field-table">
          <thead><tr><th>Field</th><th>What it does</th></tr></thead>
          <tbody>
            <tr><td class="field-name">Site Name</td><td>Shown in the browser tab and various places across the site.</td></tr>
            <tr><td class="field-name">Tagline</td><td>The script-style line shown on the homepage and footer ("Your Style Our Priority").</td></tr>
            <tr><td class="field-name">Logo (dark background)</td><td>Used in the header, footer, and emails — should be a light/white logo.</td></tr>
            <tr><td class="field-name">Logo (light background)</td><td>Used as the browser tab icon (favicon) — should be a dark logo on transparent or white.</td></tr>
            <tr><td class="field-name">Promo Bar Text</td><td>The scrolling message strip shown above the header.</td></tr>
            <tr><td class="field-name">Contact Phone / Email</td><td>Shown on the Customer Care page and used as the reply-to for contact form messages.</td></tr>
            <tr><td class="field-name">Shipping Fee</td><td>The flat delivery fee added at checkout.</td></tr>
            <tr><td class="field-name">USD Exchange Rate</td><td>How many Naira equal $1 — used anywhere prices show a USD estimate.</td></tr>
            <tr><td class="field-name">Instagram / WhatsApp / TikTok URL</td><td>Social links shown in the header and footer.</td></tr>
          </tbody>
        </table>
      </div>

      <h3>Homepage text</h3>
      <p>Further down the same page, you can edit the wording of two homepage section headings ("New Arrivals" and "Shop By Category") and the longer brand philosophy paragraph shown near the bottom of the homepage.</p>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/15-settings-mid.png" alt="Homepage text settings and Flutterwave public key field">
          <figcaption>Homepage Text section, and the Flutterwave payment key field just below it.</figcaption>
        </figure>
      </div>

      <h3>Payments (Flutterwave)</h3>
      <p>Only the <strong>Public Key</strong> is editable here — it isn't secret, so it's safe to update yourself. The Secret Key, Encryption Key, and Secret Hash can move real money and verify payments, so for security they live only in a protected file on the server, not in this panel. Ask your developer if those ever need to change.</p>

      <h3>Outgoing mail</h3>
      <p>Controls how the store sends emails — order confirmations, contact form messages, password resets.</p>
      <ul class="plain">
        <li><strong>Send Method</strong> — choose <span class="code-inline">SMTP</span> to send through a real mail provider (Gmail, Zoho, SendGrid, etc.), <span class="code-inline">Sendmail</span> to use the web server's own built-in mail sending, or <span class="code-inline">Log only</span> for testing, where no real email goes out.</li>
        <li>If you choose SMTP, fill in the <strong>Host</strong>, <strong>Port</strong>, <strong>Username</strong>, <strong>Password</strong>, and <strong>Encryption</strong> given to you by your email provider.</li>
        <li><strong>"From" Email Address / Name</strong> is what customers see as the sender on every email the store sends.</li>
        <li>Click <strong>Save &amp; Send Test Email</strong> any time to save your changes and immediately fire a test email to your own admin address, so you know right away whether it worked.</li>
      </ul>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/16-settings-mail.png" alt="Mail settings section and live chat widget code box">
          <figcaption>Mail settings with the test-email button, and the Live Chat section below it.</figcaption>
        </figure>
      </div>

      <div class="callout warn">
        <span class="icon">!</span>
        <div>The mail password field never shows your saved password back to you, even after saving — that's normal. Leave it blank when saving other changes to keep the existing password; only type into it when you want to replace it.</div>
      </div>

      <h3>Live chat</h3>
      <p>Paste the full script snippet from your live chat provider (Smartsupp, Tawk.to, etc.) into <strong>Chat Widget Code</strong> and save — it appears on every page of the storefront immediately, no developer needed. Only ever paste code copied directly from a provider you trust.</p>

      <div class="shot">
        <figure>
          <img src="/images/admin-guide/17-settings-chat.png" alt="Live chat widget code field with save settings button"/>
          <figcaption>Live Chat — paste, save, done.</figcaption>
        </figure>
      </div>
    </section>

    <section class="card" id="reference">
      <div class="kicker"><span class="idx">11</span></div>
      <h2>Quick Reference</h2>
      <p class="sub">What each order status means, and a couple of common questions.</p>

      <h3>Order statuses explained</h3>
      <div class="field-table-wrap">
        <table class="field-table">
          <thead><tr><th>Status</th><th>Meaning</th></tr></thead>
          <tbody>
            <tr><td class="field-name"><span class="status-chip s-pending">Pending</span></td><td>Order created, payment not yet confirmed. Normal for the first few seconds after checkout.</td></tr>
            <tr><td class="field-name"><span class="status-chip s-paid">Paid</span></td><td>Payment confirmed. Time to start preparing the order.</td></tr>
            <tr><td class="field-name"><span class="status-chip s-processing">Processing</span></td><td>You've started packing / preparing the order for dispatch.</td></tr>
            <tr><td class="field-name"><span class="status-chip s-shipped">Shipped</span></td><td>Order has left the building, on its way to the customer.</td></tr>
            <tr><td class="field-name"><span class="status-chip s-delivered">Delivered</span></td><td>Customer has received it. The order is complete.</td></tr>
            <tr><td class="field-name"><span class="status-chip s-cancelled">Cancelled</span></td><td>Order will not be fulfilled — stock is not affected automatically, so adjust manually if needed.</td></tr>
            <tr><td class="field-name"><span class="status-chip s-refunded">Refunded</span></td><td>Money has been returned to the customer outside the site (via Flutterwave directly).</td></tr>
            <tr><td class="field-name"><span class="status-chip s-failed">Failed</span></td><td>Payment did not go through. No action needed — the customer will need to try again themselves.</td></tr>
          </tbody>
        </table>
      </div>

      <h3>Common questions</h3>
      <p><strong>A product isn't showing on the site.</strong> Check its Status on the Products page — it needs to be <span class="code-inline">Active</span> to appear in the shop.</p>
      <p><strong>I want to temporarily stop selling something without deleting it.</strong> Change its status to <span class="code-inline">Sold Out</span> or <span class="code-inline">Archived</span> instead of deleting — the record (and its order history) stays intact.</p>
      <p><strong>A coupon isn't working for a customer.</strong> Check it's still <span class="code-inline">Enabled</span>, hasn't passed its <span class="code-inline">Expires At</span> date, hasn't hit its <span class="code-inline">Usage Limit</span>, and that the cart total meets its <span class="code-inline">Min Order Amount</span>.</p>
      <p><strong>Emails aren't arriving.</strong> Go to <a href="#settings">Settings → Mail</a> and use <strong>Save &amp; Send Test Email</strong> — if the test email doesn't arrive either, double-check the SMTP details with your email provider.</p>
    </section>

    <footer class="end">
      This guide covers day-to-day store management. For anything involving the server, deployment, or code changes, reach out to your developer.
    </footer>

  </div>
</main>

<script>
  (function () {
    var links = document.querySelectorAll('.toc nav a[href^="#"]');
    var sections = Array.prototype.map.call(links, function (a) {
      return document.querySelector(a.getAttribute('href'));
    });

    function onScroll() {
      var pos = window.scrollY + 120;
      var current = sections[0];
      sections.forEach(function (sec, i) {
        if (sec && sec.offsetTop <= pos) current = sec;
      });
      links.forEach(function (a, i) {
        a.classList.toggle('active', sections[i] === current);
      });
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  })();

  // Mobile sidebar toggle — the sidebar is fixed/always-visible on desktop;
  // on narrow screens it slides in via this button + scrim instead.
  (function () {
    var toc = document.getElementById('toc');
    var toggle = document.getElementById('menuToggle');
    var scrim = document.getElementById('tocScrim');
    if (!toc || !toggle || !scrim) return;

    function setOpen(open) {
      toc.classList.toggle('open', open);
      scrim.classList.toggle('open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggle.textContent = open ? '✕' : '☰';
    }

    toggle.addEventListener('click', function () { setOpen(!toc.classList.contains('open')); });
    scrim.addEventListener('click', function () { setOpen(false); });
    toc.querySelectorAll('a[href^="#"]').forEach(function (a) {
      a.addEventListener('click', function () { setOpen(false); });
    });
  })();
</script>

@endverbatim
