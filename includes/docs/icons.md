# Icon listing

The application-wide icon vocabulary. Every package draws icons with
`{booticon iname="fa-<name>"}` and picks `<name>` from this file, so the
same action shows the same icon everywhere and the icon set can be swapped
without touching a template.

## The naming rule

Use only names that exist in **both** Font Awesome (Pro, light face) and
Line Awesome 1.3. Line Awesome is a free outline set that keeps Font Awesome
5 names (`la-<name>`), and its line weight matches the Font Awesome light
face. An install with a Font Awesome Pro kit renders the light face; an
install without one can render the same names with Line Awesome. A name
outside the overlap draws nothing on one of the two.

- 1443 outline names and 430 brand names are in the overlap; both lists are
  below. The count was taken against Font Awesome 7.3 and Line Awesome 1.3.0
  by comparing stylesheet names and code points. Re-run the comparison when
  either set is upgraded.
- Write the Font Awesome 5 spelling (`fa-edit`, `fa-trash-alt`, `fa-cog`,
  `fa-search`). Later Font Awesome releases keep these as aliases of the
  renamed icons (`fa-pen-to-square`, `fa-trash-can`, `fa-gear`,
  `fa-magnifying-glass`); Line Awesome knows only the version 5 spelling.
- No Font Awesome 3 or 4 names (`icon-*`, `fa-*-o`, `fa-warning-sign`). No
  Pro-only names (`fa-image-landscape`, `fa-folder-image`, duotone or sharp
  families).
- A brand mark needs `iclass="fa-brands"` in `{booticon}`.
- `{booticon}` currently emits `class="fa fal fa-<name>"`. Emitting the
  Line Awesome prefix (`la la-<name>`) from a site setting is not built yet;
  until it is, a Line Awesome install maps the classes in its theme.

## Shared action vocabulary

Pick the icon by what the control does, not by what it looks like. When an
action is missing, choose a name from the overlap list and add the row here
in the same change.

| Action | `iname` |
|---|---|
| Edit, rename | `fa-edit` |
| Delete | `fa-trash-alt` |
| Remove from a set (keep the item) | `fa-minus-circle` |
| Add, create | `fa-plus-circle` |
| Upload | `fa-upload` |
| Download | `fa-download` |
| Save | `fa-save` |
| Copy | `fa-copy` |
| Move to | `fa-folder-open` |
| Settings | `fa-cog` |
| Options, adjust | `fa-sliders-h` |
| Search | `fa-search` |
| Zoom in | `fa-search-plus` |
| Filter | `fa-filter` |
| Sort | `fa-sort` |
| Sort descending | `fa-sort-amount-down` |
| Close, cancel | `fa-times` |
| Confirm, done | `fa-check` |
| Selected | `fa-check-circle` |
| More actions | `fa-ellipsis-h` |
| Menu | `fa-bars` |
| Previous / next | `fa-angle-left`, `fa-angle-right` |
| Back | `fa-arrow-left` |
| Expand to full screen / leave | `fa-expand`, `fa-compress` |
| Rotate left / right | `fa-undo`, `fa-redo` |
| Refresh, regenerate | `fa-sync-alt` |
| Shuffle | `fa-random` |
| Reorder handle | `fa-grip-vertical` |
| Folder, collection | `fa-folder` |
| New collection | `fa-folder-plus` |
| Image | `fa-image` |
| Images, gallery | `fa-images` |
| Cover, featured | `fa-star` |
| Favorite | `fa-heart` |
| Grid view | `fa-th` |
| Light / dark appearance | `fa-sun`, `fa-moon` |
| Row / list view | `fa-th-list` |
| Column view | `fa-th-large` |
| Show or hide a side panel | `fa-columns` |
| Slideshow | `fa-play` |
| Date | `fa-calendar-alt` |
| Time, recent | `fa-clock` |
| History | `fa-history` |
| Place | `fa-map-marker-alt` |
| Map | `fa-map-marked-alt` |
| Camera, device | `fa-camera` |
| Tag | `fa-tag` |
| Comment | `fa-comment` |
| Share | `fa-share-alt` |
| Link | `fa-link` |
| Opens elsewhere | `fa-external-link-alt` |
| Visible / hidden | `fa-eye`, `fa-eye-slash` |
| Private / public | `fa-lock`, `fa-unlock` |
| Person / people | `fa-user`, `fa-users` |
| Print | `fa-print` |
| Archive, zip | `fa-file-archive` |
| Information | `fa-info-circle` |
| Help | `fa-question-circle` |
| Warning | `fa-exclamation-triangle` |
| Error | `fa-exclamation-circle` |
| Home | `fa-home` |
| Feed | `fa-rss` |

## Parity notes

The vocabulary above was rendered side by side in both sets on 2026-11-10.
Same name, noticeably different drawing:

- `fa-sidebar` is Pro-only; the side panel toggle is `fa-columns`.
- `fa-clone` draws an unrelated figure in Line Awesome; use `fa-copy`.
- `fa-chevron-left` and `fa-chevron-right` are double chevrons in Line
  Awesome; `fa-angle-left` and `fa-angle-right` are single in both.
- `fa-star`, `fa-heart` and `fa-rss` are filled in Line Awesome's solid
  face and outlined in its regular face (`lar`). A Line Awesome install
  should map those three to the regular face.
- `fa-undo` and `fa-redo` are circular arrows in Font Awesome and bent
  arrows in Line Awesome. Both read as rotate; accepted.

## Names to avoid

These 70 names exist in both sets but resolve to different code points, so the
drawing can change with the icon set. Use the stable neighbour
(`fa-expand-arrows-alt` rather than `fa-arrows-alt`, `fa-calendar-alt`
rather than `fa-calendar`, `fa-trash-alt` rather than `fa-trash`,
`fa-upload` rather than `fa-cloud-upload-alt`).

```
arrows, arrows-alt, arrows-h, arrows-v, battery-1, battery-2, battery-3,
battery-4, bitcoin, calendar, circle-o, cloud-download, cloud-download-alt,
cloud-upload, cloud-upload-alt, code-fork, dashboard, desktop, diamond,
exchange, external-link, external-link-square, facebook, font-awesome-flag,
font-awesome-logo-full, glass, google-plus, hands, headphones-alt,
hospital-alt, hotel, inr, level-down, level-up, linkedin, long-arrow-down,
long-arrow-left, long-arrow-right, long-arrow-up, map-marker, medium-m,
mobile, mobile-phone, paste, reorder, repeat, rotate-left, rotate-right,
send, shield, sign-in, sign-out, slack-hash, snapchat-ghost, square-o,
tablet, tachometer, tachometer-alt, telegram-plane, thermometer, ticket,
transgender, trash, try, turkish-lira, user-alt, user-alt-slash,
vector-square, vimeo, wheelchair-alt
```

## Overlap: outline names

Prefix each with `fa-`.

```
500px, accessible-icon, accusoft, ad, address-book, address-card, adjust,
adn, adversal, affiliatetheme, air-freshener, airbnb, algolia, align-center,
align-justify, align-left, align-right, alipay, allergies, amazon,
amazon-pay, ambulance, american-sign-language-interpreting, amilia, anchor,
android, angellist, angle-double-down, angle-double-left,
angle-double-right, angle-double-up, angle-down, angle-left, angle-right,
angle-up, angry, angrycreative, angular, ankh, app-store, app-store-ios,
apper, apple, apple-alt, apple-pay, archive, archway, area-chart,
arrow-alt-circle-down, arrow-alt-circle-left, arrow-alt-circle-right,
arrow-alt-circle-up, arrow-circle-down, arrow-circle-left,
arrow-circle-right, arrow-circle-up, arrow-down, arrow-left, arrow-right,
arrow-up, arrows-alt-h, arrows-alt-v, artstation, asl-interpreting,
assistive-listening-systems, asterisk, asymmetrik, at, atlas, atlassian,
atom, audible, audio-description, automobile, autoprefixer, avianex, aviato,
award, aws, baby, baby-carriage, backspace, backward, bacon, balance-scale,
balance-scale-left, balance-scale-right, ban, band-aid, bandcamp, bank,
bar-chart, barcode, bars, baseball-ball, basketball-ball, bath, bathtub,
battery, battery-0, battery-empty, battery-full, battery-half,
battery-quarter, battery-three-quarters, battle-net, bed, beer, behance,
behance-square, bell, bell-slash, bezier-curve, bible, bicycle, biking,
bimobject, binoculars, biohazard, birthday-cake, bitbucket, bity, black-tie,
blackberry, blender, blender-phone, blind, blog, blogger, blogger-b,
bluetooth, bluetooth-b, bold, bolt, bomb, bone, bong, book, book-dead,
book-medical, book-open, book-reader, bookmark, bootstrap, border-all,
border-none, border-style, bowling-ball, box, box-open, boxes, braille,
brain, bread-slice, briefcase, briefcase-medical, broadcast-tower, broom,
brush, btc, buffer, bug, building, bullhorn, bullseye, burn,
buromobelexperte, bus, bus-alt, business-time, buy-n-large, buysellads, cab,
calculator, calendar-alt, calendar-check, calendar-day, calendar-minus,
calendar-plus, calendar-times, calendar-week, camera, camera-retro,
campground, canadian-maple-leaf, candy-cane, cannabis, capsules, car,
car-alt, car-battery, car-crash, car-side, caret-down, caret-left,
caret-right, caret-square-down, caret-square-left, caret-square-right,
caret-square-up, caret-up, carrot, cart-arrow-down, cart-plus,
cash-register, cat, cc-amazon-pay, cc-amex, cc-apple-pay, cc-diners-club,
cc-discover, cc-jcb, cc-mastercard, cc-paypal, cc-stripe, cc-visa,
centercode, centos, certificate, chain, chain-broken, chair, chalkboard,
chalkboard-teacher, charging-station, chart-area, chart-bar, chart-line,
chart-pie, check, check-circle, check-double, check-square, cheese, chess,
chess-bishop, chess-board, chess-king, chess-knight, chess-pawn,
chess-queen, chess-rook, chevron-circle-down, chevron-circle-left,
chevron-circle-right, chevron-circle-up, chevron-down, chevron-left,
chevron-right, chevron-up, child, chrome, chromecast, church, circle,
circle-notch, city, clinic-medical, clipboard, clipboard-check,
clipboard-list, clock, clone, close, closed-captioning, cloud,
cloud-meatball, cloud-moon, cloud-moon-rain, cloud-rain,
cloud-showers-heavy, cloud-sun, cloud-sun-rain, cloudscale, cloudsmith,
cloudversify, cny, cocktail, code, code-branch, codepen, codiepie, coffee,
cog, cogs, coins, columns, comment, comment-alt, comment-dollar,
comment-dots, comment-medical, comment-slash, commenting, comments,
comments-dollar, compact-disc, compass, compress, compress-arrows-alt,
concierge-bell, confluence, connectdevelop, contao, cookie, cookie-bite,
copy, copyright, cotton-bureau, couch, cpanel, creative-commons,
creative-commons-by, creative-commons-nc, creative-commons-nc-eu,
creative-commons-nc-jp, creative-commons-nd, creative-commons-pd,
creative-commons-pd-alt, creative-commons-remix, creative-commons-sa,
creative-commons-sampling, creative-commons-sampling-plus,
creative-commons-share, creative-commons-zero, credit-card, credit-card-alt,
critical-role, crop, crop-alt, cross, crosshairs, crow, crown, crutch, css3,
css3-alt, cube, cubes, cut, cutlery, cuttlefish, d-and-d, d-and-d-beyond,
dashcube, database, deaf, deafness, dedent, delicious, democrat, deploydog,
deskpro, dev, deviantart, dharmachakra, dhl, diagnoses, diaspora, dice,
dice-d20, dice-d6, dice-five, dice-four, dice-one, dice-six, dice-three,
dice-two, digg, digital-ocean, digital-tachograph, directions, discord,
discourse, divide, dizzy, dna, dochub, docker, dog, dollar, dollar-sign,
dolly, dolly-flatbed, donate, door-closed, door-open, dot-circle, dove,
download, draft2digital, drafting-compass, dragon, draw-polygon, dribbble,
dribbble-square, drivers-license, dropbox, drum, drum-steelpan,
drumstick-bite, drupal, dumbbell, dumpster, dumpster-fire, dungeon, dyalog,
earlybirds, ebay, edge, edit, egg, eject, elementor, ellipsis-h, ellipsis-v,
ello, ember, empire, envelope, envelope-open, envelope-open-text,
envelope-square, envira, equals, eraser, erlang, ethereum, ethernet, etsy,
eur, euro, euro-sign, evernote, exchange-alt, exclamation,
exclamation-circle, exclamation-triangle, expand, expand-arrows-alt,
expeditedssl, external-link-alt, external-link-square-alt, eye, eye-dropper,
eye-slash, eyedropper, facebook-f, facebook-messenger, facebook-square, fan,
fantasy-flight-games, fast-backward, fast-forward, fax, feather,
feather-alt, fedex, fedora, feed, female, fighter-jet, figma, file,
file-alt, file-archive, file-audio, file-code, file-contract, file-csv,
file-download, file-excel, file-export, file-image, file-import,
file-invoice, file-invoice-dollar, file-medical, file-medical-alt, file-pdf,
file-powerpoint, file-prescription, file-signature, file-text, file-upload,
file-video, file-word, fill, fill-drip, film, filter, fingerprint, fire,
fire-alt, fire-extinguisher, firefox, first-aid, first-order,
first-order-alt, firstdraft, fish, fist-raised, flag, flag-checkered,
flag-usa, flask, flickr, flipboard, flushed, fly, folder, folder-minus,
folder-open, folder-plus, font, font-awesome, font-awesome-alt, fonticons,
fonticons-fi, football-ball, fort-awesome, fort-awesome-alt, forumbee,
forward, foursquare, free-code-camp, freebsd, frog, frown, frown-open,
fulcrum, funnel-dollar, futbol, galactic-republic, galactic-senate, gamepad,
gas-pump, gavel, gbp, gear, gears, gem, genderless, get-pocket, gg,
gg-circle, ghost, gift, gifts, git, git-alt, git-square, github, github-alt,
github-square, gitkraken, gitlab, gitter, glass-cheers, glass-martini,
glass-martini-alt, glass-whiskey, glasses, glide, glide-g, globe,
globe-africa, globe-americas, globe-asia, globe-europe, gofore, golf-ball,
goodreads, goodreads-g, google, google-drive, google-play, google-plus-g,
google-plus-square, google-wallet, gopuram, graduation-cap, gratipay, grav,
greater-than, greater-than-equal, grimace, grin, grin-alt, grin-beam,
grin-beam-sweat, grin-hearts, grin-squint, grin-squint-tears, grin-stars,
grin-tears, grin-tongue, grin-tongue-squint, grin-tongue-wink, grin-wink,
grip-horizontal, grip-lines, grip-lines-vertical, grip-vertical, gripfire,
grunt, guitar, gulp, h-square, hacker-news, hacker-news-square, hackerrank,
hamburger, hammer, hamsa, hand-holding, hand-holding-heart,
hand-holding-usd, hand-lizard, hand-middle-finger, hand-paper, hand-peace,
hand-point-down, hand-point-left, hand-point-right, hand-point-up,
hand-pointer, hand-rock, hand-scissors, hand-spock, hands-helping,
handshake, hanukiah, hard-hat, hard-of-hearing, hashtag, hat-cowboy,
hat-cowboy-side, hat-wizard, haykal, hdd, header, heading, headphones,
headset, heart, heart-broken, heartbeat, helicopter, highlighter, hiking,
hippo, hips, hire-a-helper, history, hockey-puck, holly-berry, home, hooli,
hornbill, horse, horse-head, hospital, hospital-symbol, hot-tub, hotdog,
hotjar, hourglass, hourglass-1, hourglass-2, hourglass-3, hourglass-end,
hourglass-half, hourglass-start, house-damage, houzz, hryvnia, html5,
hubspot, i-cursor, ice-cream, icicles, icons, id-badge, id-card,
id-card-alt, igloo, ils, image, images, imdb, inbox, indent, industry,
infinity, info, info-circle, instagram, institution, intercom,
internet-explorer, invision, ioxhost, italic, itch-io, itunes, itunes-note,
java, jedi, jedi-order, jenkins, jira, joget, joint, joomla, journal-whills,
jpy, js, js-square, jsfiddle, kaaba, kaggle, key, keybase, keyboard, keycdn,
khanda, kickstarter, kickstarter-k, kiss, kiss-beam, kiss-wink-heart,
kiwi-bird, korvue, krw, landmark, language, laptop, laptop-code,
laptop-medical, laravel, lastfm, lastfm-square, laugh, laugh-beam,
laugh-squint, laugh-wink, layer-group, leaf, leanpub, legal, lemon, less,
less-than, less-than-equal, level-down-alt, level-up-alt, life-ring,
lightbulb, line, line-chart, link, linkedin-in, linode, linux, lira-sign,
list, list-alt, list-ol, list-ul, location-arrow, lock, lock-open,
long-arrow-alt-down, long-arrow-alt-left, long-arrow-alt-right,
long-arrow-alt-up, low-vision, luggage-cart, lyft, magento, magic, magnet,
mail-bulk, mail-forward, mail-reply, mail-reply-all, mailchimp, male,
mandalorian, map, map-marked, map-marked-alt, map-marker-alt, map-pin,
map-signs, markdown, marker, mars, mars-double, mars-stroke, mars-stroke-h,
mars-stroke-v, mask, mastodon, maxcdn, mdb, medal, medapps, medium, medkit,
medrt, meetup, megaport, meh, meh-blank, meh-rolling-eyes, memory, mendeley,
menorah, mercury, meteor, microchip, microphone, microphone-alt,
microphone-alt-slash, microphone-slash, microscope, microsoft, minus,
minus-circle, minus-square, mitten, mix, mixcloud, mizuni, mobile-alt, modx,
monero, money-bill, money-bill-alt, money-bill-wave, money-bill-wave-alt,
money-check, money-check-alt, monument, moon, mortar-board, mortar-pestle,
mosque, motorcycle, mountain, mouse, mouse-pointer, mug-hot, music, napster,
navicon, neos, network-wired, neuter, newspaper, nimblr, node, node-js,
not-equal, notes-medical, npm, ns8, nutritionix, object-group,
object-ungroup, odnoklassniki, odnoklassniki-square, oil-can, old-republic,
om, opencart, openid, opera, optin-monster, orcid, osi, otter, outdent,
page4, pagelines, pager, paint-brush, paint-roller, palette, palfed, pallet,
paper-plane, paperclip, parachute-box, paragraph, parking, passport,
pastafarianism, patreon, pause, pause-circle, paw, paypal, peace, pen,
pen-alt, pen-fancy, pen-nib, pen-square, pencil, pencil-alt, pencil-ruler,
pencil-square, people-carry, pepper-hot, percent, percentage, periscope,
person-booth, phabricator, phoenix-framework, phoenix-squadron, phone,
phone-alt, phone-slash, phone-square, phone-square-alt, phone-volume,
photo-video, php, pie-chart, pied-piper, pied-piper-alt, pied-piper-hat,
pied-piper-pp, piggy-bank, pills, pinterest, pinterest-p, pinterest-square,
pizza-slice, place-of-worship, plane, plane-arrival, plane-departure, play,
play-circle, playstation, plug, plus, plus-circle, plus-square, podcast,
poll, poll-h, poo, poo-storm, poop, portrait, pound-sign, power-off, pray,
praying-hands, prescription, prescription-bottle, prescription-bottle-alt,
print, procedures, product-hunt, project-diagram, pushed, puzzle-piece,
python, qq, qrcode, question, question-circle, quidditch, quinscape, quora,
quote-left, quote-right, quran, r-project, radiation, radiation-alt,
rainbow, random, raspberry-pi, ravelry, react, reacteurope, readme, rebel,
receipt, record-vinyl, recycle, red-river, reddit, reddit-alien,
reddit-square, redhat, redo, redo-alt, refresh, registered, remove,
remove-format, renren, reply, reply-all, replyd, republican, researchgate,
resolving, restroom, retweet, rev, ribbon, ring, rmb, road, robot, rocket,
rocketchat, rockrms, rouble, route, rss, rss-square, rub, ruble, ruble-sign,
ruler, ruler-combined, ruler-horizontal, ruler-vertical, running, rupee,
rupee-sign, sad-cry, sad-tear, safari, salesforce, sass, satellite,
satellite-dish, save, schlix, school, scissors, screwdriver, scribd, scroll,
sd-card, search, search-dollar, search-location, search-minus, search-plus,
searchengin, seedling, sellcast, sellsy, server, servicestack, shapes,
share, share-alt, share-alt-square, share-square, shekel, shekel-sign,
sheqel, shield-alt, ship, shipping-fast, shirtsinbulk, shoe-prints,
shopping-bag, shopping-basket, shopping-cart, shopware, shower, shuttle-van,
sign, sign-in-alt, sign-language, sign-out-alt, signal, signature, signing,
sim-card, simplybuilt, sistrix, sitemap, sith, skating, sketch, skiing,
skiing-nordic, skull, skull-crossbones, skyatlas, skype, slack, slash,
sleigh, sliders, sliders-h, slideshare, smile, smile-beam, smile-wink, smog,
smoking, smoking-ban, sms, snapchat, snapchat-square, snowboarding,
snowflake, snowman, snowplow, socks, solar-panel, sort, sort-alpha-asc,
sort-alpha-desc, sort-alpha-down, sort-alpha-down-alt, sort-alpha-up,
sort-alpha-up-alt, sort-amount-asc, sort-amount-desc, sort-amount-down,
sort-amount-down-alt, sort-amount-up, sort-amount-up-alt, sort-asc,
sort-desc, sort-down, sort-numeric-asc, sort-numeric-desc,
sort-numeric-down, sort-numeric-down-alt, sort-numeric-up,
sort-numeric-up-alt, sort-up, soundcloud, sourcetree, spa, space-shuttle,
speakap, speaker-deck, spell-check, spider, spinner, splotch, spoon,
spotify, spray-can, square, square-full, square-root-alt, squarespace,
stack-exchange, stack-overflow, stackpath, stamp, star, star-and-crescent,
star-half, star-half-alt, star-of-david, star-of-life, staylinked, steam,
steam-square, steam-symbol, step-backward, step-forward, stethoscope,
sticker-mule, sticky-note, stop, stop-circle, stopwatch, store, store-alt,
strava, stream, street-view, strikethrough, stripe, stripe-s, stroopwafel,
studiovinari, stumbleupon, stumbleupon-circle, subscript, subway, suitcase,
suitcase-rolling, sun, superpowers, superscript, supple, surprise, suse,
swatchbook, swift, swimmer, swimming-pool, symfony, synagogue, sync,
sync-alt, syringe, table, table-tennis, tablet-alt, tablets, tag, tags,
tape, tasks, taxi, teamspeak, teeth, teeth-open, telegram, television,
temperature-high, temperature-low, tencent-weibo, tenge, terminal,
text-height, text-width, th, th-large, th-list, the-red-yeti, theater-masks,
themeco, themeisle, thermometer-0, thermometer-1, thermometer-2,
thermometer-3, thermometer-4, thermometer-empty, thermometer-full,
thermometer-half, thermometer-quarter, thermometer-three-quarters,
think-peaks, thumb-tack, thumbs-down, thumbs-up, thumbtack, ticket-alt,
times, times-circle, times-rectangle, tint, tint-slash, tired, toggle-off,
toggle-on, toilet, toilet-paper, toolbox, tools, tooth, torah, torii-gate,
tractor, trade-federation, trademark, traffic-light, train, tram,
transgender-alt, trash-alt, trash-restore, trash-restore-alt, tree, trello,
trophy, truck, truck-loading, truck-monster, truck-moving, truck-pickup,
tshirt, tty, tumblr, tumblr-square, tv, twitch, twitter, twitter-square,
typo3, uber, ubuntu, uikit, umbraco, umbrella, umbrella-beach, underline,
undo, undo-alt, uniregistry, universal-access, university, unlink, unlock,
unlock-alt, unsorted, untappd, upload, ups, usb, usd, user, user-astronaut,
user-check, user-circle, user-clock, user-cog, user-edit, user-friends,
user-graduate, user-injured, user-lock, user-md, user-minus, user-ninja,
user-nurse, user-plus, user-secret, user-shield, user-slash, user-tag,
user-tie, user-times, users, users-cog, usps, ussunnah, utensil-spoon,
utensils, vaadin, vcard, venus, venus-double, venus-mars, viacoin, viadeo,
viadeo-square, vial, vials, viber, video, video-camera, video-slash, vihara,
vimeo-square, vimeo-v, vine, vk, vnv, voicemail, volleyball-ball,
volume-control-phone, volume-down, volume-mute, volume-off, volume-up,
vote-yea, vr-cardboard, vuejs, walking, wallet, warehouse, warning, water,
wave-square, waze, weebly, weibo, weight, weight-hanging, weixin, whatsapp,
whatsapp-square, wheelchair, whmcs, wifi, wikipedia-w, wind, window-close,
window-maximize, window-minimize, window-restore, windows, wine-bottle,
wine-glass, wine-glass-alt, wix, wizards-of-the-coast, wolf-pack-battalion,
won, won-sign, wordpress, wordpress-simple, wpbeginner, wpexplorer, wpforms,
wpressr, wrench, x-ray, xbox, xing, xing-square, y-combinator, yahoo,
yammer, yandex, yandex-international, yarn, yelp, yen, yen-sign, yin-yang,
yoast, youtube, youtube-square, zhihu
```

## Overlap: brand names

Prefix each with `fa-` and pass `iclass="fa-brands"`.

```
500px, accessible-icon, accusoft, adn, adversal, affiliatetheme, airbnb,
algolia, alipay, amazon, amazon-pay, amilia, android, angellist,
angrycreative, angular, app-store, app-store-ios, apper, apple, apple-pay,
artstation, asymmetrik, atlassian, audible, autoprefixer, avianex, aviato,
aws, bandcamp, battle-net, behance, behance-square, bimobject, bitbucket,
bitcoin, bity, black-tie, blackberry, blogger, blogger-b, bluetooth,
bluetooth-b, bootstrap, btc, buffer, buromobelexperte, buy-n-large,
buysellads, canadian-maple-leaf, cc-amazon-pay, cc-amex, cc-apple-pay,
cc-diners-club, cc-discover, cc-jcb, cc-mastercard, cc-paypal, cc-stripe,
cc-visa, centercode, centos, chrome, chromecast, cloudscale, cloudsmith,
cloudversify, codepen, codiepie, confluence, connectdevelop, contao,
cotton-bureau, cpanel, creative-commons, creative-commons-by,
creative-commons-nc, creative-commons-nc-eu, creative-commons-nc-jp,
creative-commons-nd, creative-commons-pd, creative-commons-pd-alt,
creative-commons-remix, creative-commons-sa, creative-commons-sampling,
creative-commons-sampling-plus, creative-commons-share,
creative-commons-zero, critical-role, css3, css3-alt, cuttlefish, d-and-d,
d-and-d-beyond, dashcube, delicious, deploydog, deskpro, dev, deviantart,
dhl, diaspora, digg, digital-ocean, discord, discourse, dochub, docker,
draft2digital, dribbble, dribbble-square, dropbox, drupal, dyalog,
earlybirds, ebay, edge, elementor, ello, ember, empire, envira, erlang,
ethereum, etsy, evernote, expeditedssl, facebook, facebook-f,
facebook-messenger, facebook-square, fantasy-flight-games, fedex, fedora,
figma, firefox, first-order, first-order-alt, firstdraft, flickr, flipboard,
fly, font-awesome, font-awesome-alt, font-awesome-flag,
font-awesome-logo-full, fonticons, fonticons-fi, fort-awesome,
fort-awesome-alt, forumbee, foursquare, free-code-camp, freebsd, fulcrum,
galactic-republic, galactic-senate, get-pocket, gg, gg-circle, git, git-alt,
git-square, github, github-alt, github-square, gitkraken, gitlab, gitter,
glide, glide-g, gofore, goodreads, goodreads-g, google, google-drive,
google-play, google-plus, google-plus-g, google-plus-square, google-wallet,
gratipay, grav, gripfire, grunt, gulp, hacker-news, hacker-news-square,
hackerrank, hips, hire-a-helper, hooli, hornbill, hotjar, houzz, html5,
hubspot, imdb, instagram, intercom, internet-explorer, invision, ioxhost,
itch-io, itunes, itunes-note, java, jedi-order, jenkins, jira, joget,
joomla, js, js-square, jsfiddle, kaggle, keybase, keycdn, kickstarter,
kickstarter-k, korvue, laravel, lastfm, lastfm-square, leanpub, less, line,
linkedin, linkedin-in, linode, linux, lyft, magento, mailchimp, mandalorian,
markdown, mastodon, maxcdn, mdb, medapps, medium, medium-m, medrt, meetup,
megaport, mendeley, microsoft, mix, mixcloud, mizuni, modx, monero, napster,
neos, nimblr, node, node-js, npm, ns8, nutritionix, odnoklassniki,
odnoklassniki-square, old-republic, opencart, openid, opera, optin-monster,
orcid, osi, page4, pagelines, palfed, patreon, paypal, periscope,
phabricator, phoenix-framework, phoenix-squadron, php, pied-piper,
pied-piper-alt, pied-piper-hat, pied-piper-pp, pinterest, pinterest-p,
pinterest-square, playstation, product-hunt, pushed, python, qq, quinscape,
quora, r-project, raspberry-pi, ravelry, react, reacteurope, readme, rebel,
red-river, reddit, reddit-alien, reddit-square, redhat, renren, replyd,
researchgate, resolving, rev, rocketchat, rockrms, safari, salesforce, sass,
schlix, scribd, searchengin, sellcast, sellsy, servicestack, shirtsinbulk,
shopware, simplybuilt, sistrix, sith, sketch, skyatlas, skype, slack,
slack-hash, slideshare, snapchat, snapchat-ghost, snapchat-square,
soundcloud, sourcetree, speakap, speaker-deck, spotify, squarespace,
stack-exchange, stack-overflow, stackpath, staylinked, steam, steam-square,
steam-symbol, sticker-mule, strava, stripe, stripe-s, studiovinari,
stumbleupon, stumbleupon-circle, superpowers, supple, suse, swift, symfony,
teamspeak, telegram, telegram-plane, tencent-weibo, the-red-yeti, themeco,
themeisle, think-peaks, trade-federation, trello, tumblr, tumblr-square,
twitch, twitter, twitter-square, typo3, uber, ubuntu, uikit, umbraco,
uniregistry, untappd, ups, usb, usps, ussunnah, vaadin, viacoin, viadeo,
viadeo-square, viber, vimeo, vimeo-square, vimeo-v, vine, vk, vnv, vuejs,
waze, weebly, weibo, weixin, whatsapp, whatsapp-square, whmcs, wikipedia-w,
windows, wix, wizards-of-the-coast, wolf-pack-battalion, wordpress,
wordpress-simple, wpbeginner, wpexplorer, wpforms, wpressr, xbox, xing,
xing-square, y-combinator, yahoo, yammer, yandex, yandex-international,
yarn, yelp, yoast, youtube, youtube-square, zhihu
```
