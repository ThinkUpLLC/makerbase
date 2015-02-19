TRUNCATE makers; TRUNCATE participations; TRUNCATE products;

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('adammathes', 'adammathes', 'Adam Mathes', 'http://trenchant.org', '');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('amber', 'amber', 'Amber Costley', 'http://ambercostley.com/', 'https://si0.twimg.com/profile_images/1452719858/twit.jpg');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('anildash', 'anildash', 'Anil Dash', 'http://anildash.com/', 'https://fbcdn-sphotos-a.akamaihd.net/hphotos-ak-ash4/231103_10150204671337897_500012896_7138628_819172_n.jpg');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('apparentlymart', 'apparentlymart', 'Martin Atkins', 'http://martin.atkins.me.uk/', 'https://secure.gravatar.com/avatar/a0b347907bfaf05694805210ec595d6c?s=150');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('benbrown', 'benbrown', 'Ben Brown', 'http://benbrown.com', 'http://25.media.tumblr.com/tumblr_lmajsxoKpK1qz4en5o1_500.png');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('bradfitz', 'bradfitz', 'Brad Fitzpatrick', 'http://bradfitz.com/', 'http://l-userpic.livejournal.com/54541970/2');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('busterbenson', 'busterbenson', 'Buster Benson', 'http://busterbenson.com', 'https://en.gravatar.com/userimage/15474767/152a6b2aa17dbb629ce9deb2e640a114.jpg?size=200');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('djacobs', 'djacobs', 'David Jacobs', 'http://hello.typepad.com/', 'https://si0.twimg.com/profile_images/1909321351/IMG_1351.jpg');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('evan-williams', 'evan-williams', 'Evan Williams', 'http://www.evhead.com/', 'https://twimg0-a.akamaihd.net/profile_images/1809899595/IMG_8189.jpg__6_documents__6_total_pages_.jpg');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('ftrain', 'ftrain', 'Paul Ford', 'http://ftrain.com', 'http://www.garybenchleyrockstar.com/PaulFordHead_400x503.jpg');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('ginatrapani', 'ginatrapani', 'Gina Trapani', 'http://ginatrapani.org', 'http://ginatrapani.org/imgs/gtrapani_square_160.jpg');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('gleuch', 'gleuch', 'Greg Leuch', 'http://gleu.ch', 'http://gleuch-assets.s3.amazonaws.com/me.jpg');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('jamiew', 'jamiew', 'Jamie Wilkinson', 'http://jamiedubs.com', 'https://twimg0-a.akamaihd.net/profile_images/1215039745/jdubs-headshot-by-irene5.jpg');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('khoi-vinh', 'khoi-vinh', 'Khoi Vinh', 'http://www.subtraction.com', 'https://lh3.googleusercontent.com/-5qM7gzBtASY/Tg0uH9nA2HI/AAAAAAAAABo/RGgYVzOZvjc/s499/2010+April+-+Balcony+-+500+x+500.jpg');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('lonnon', 'lonnon', 'Lonnon Foster', 'http://lonnon.com', 'http://www.gravatar.com/avatar/9489ef302fbff6c19bba507d09f8cd1d?s=150');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('markpasc', 'markpasc', 'Mark Paschal', 'http://markpasc.org/mark/', 'https://secure.gravatar.com/avatar/30e5bdec1073df6350d27b8145bf0dab?s=140&d=https://a248.e.akamai.net/assets.github.com%2Fimages%2Fgravatars%2Fgravatar-140.png');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('matt-haughey', 'matt-haughey', 'Matt Haughey', 'http://a.wholelottanothing.org/', 'https://twimg0-a.akamaihd.net/profile_images/1707429495/wweek-headshot.jpg');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('mike-monteiro', 'mike-monteiro', 'Mike Monteiro', 'http://www.muledesign.com', 'https://si0.twimg.com/profile_images/556789661/pigman.jpg');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('paul-bausch', 'paul-bausch', 'Paul Bausch', 'http://onfocus.com', 'https://twimg0-a.akamaihd.net/profile_images/1707451084/portrait-paulbausch-crop2.png');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('personname', 'personname', 'Ben Brown', 'http://benbrown.com', 'http://25.media.tumblr.com/tumblr_lmajsxoKpK1qz4en5o1_500.png');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('tagsinput', 'tagsinput', 'jQuery Tags Input', 'http://xoxco.com/projects/code/tagsinput/', '');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('torrez', 'torrez', 'Andre Torrez', 'http://torrez.org/', 'https://si0.twimg.com/profile_images/1788942159/black-log.gif');

INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('waxpancake', 'waxpancake', 'Andy Baio', 'http://waxy.org/', 'https://twimg0-a.akamaihd.net/profile_images/1215102961/waxpancake_fedora.jpg');

INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('2daysinseattle', '2 Days in Seattle', 'How would *you* spend 2 days in Seattle?', 'http://2daysinseattle.com', 'http://i.imgur.com/vhkQN.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'lonnon';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Programmer, front and back end', '2011-12-01', '2012-05-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('43things', '43 Things', 'World''s largest community of life lists.', 'http://43things.com', 'http://acf.43things.com/images/icons/43Things.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'busterbenson';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Co-founder', '2005-01-01', '2009-05-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('750words', '750 Words', 'Online private morning pages community.', 'http://750words.com', 'http://750words.com/images/badges/badge.penguin.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'busterbenson';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator', '2009-08-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('basecamp', 'Basecamp', 'Basecamp is for storing, coordinating, and managing your company’s projects, tasks, discussions, and decisions.', 'http://basecamp.com/', 'http://basecamp.com/assets/images/logo.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'anildash';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'I suggested that the app icon be tweaked to include a checkmark.', '2004-02-01', '2004-02-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('blogger', 'Blogger', 'A personal blogging engine', 'http://www.blogger.com/', 'http://c4lpt.co.uk/Top100Tools/blogger.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'paul-bausch';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Developer', '1999-08-01', '2001-01-01');
SELECT id INTO @maker_id FROM makers WHERE slug = 'matt-haughey';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Designer', '2000-04-01', '2001-01-01');
SELECT id INTO @maker_id FROM makers WHERE slug = 'evan-williams';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Co-founder', '1999-08-01', '2005-10-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('budge', 'Budge', 'Health improvement programs that don''t let you fail', 'http://bud.ge', 'http://s3.amazonaws.com/budge_production/programs/photos/21/small_meditation-buddy.jpg');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'busterbenson';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Founder', '2012-01-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('consumating', 'Consumating', 'Consumating was a dating site for indie rock boys and girls in glasses.', 'http://consumating.com', 'http://onlinedating.wpengine.netdna-cdn.com/wp-content/uploads/consumatinglogo210n.gif');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'benbrown';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Co-founded it and was deeply engaged with the community.', '2003-03-01', '2007-04-01');
SELECT id INTO @maker_id FROM makers WHERE slug = 'adammathes';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Guy 2', '2003-04-01', '2005-11-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('convore', 'Convore', 'A quick way to communicate with groups of friends in real-time', 'http://convore.com/', 'http://www.crunchbase.com/assets/images/resized/0012/1223/121223v5-max-250x250.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'markpasc';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Contract iPhone programmer', '2011-03-01', '2011-04-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('creative-commons', 'Creative Commons', 'A new way to look at copyright and licensing your works', 'http://creativecommons.org/', 'http://creativecommons.org/images/deed/cc-logo.jpg');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'matt-haughey';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Designer', '2002-02-01', '2005-10-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('deepleap', 'Deepleap', 'Deepleap was a contextual information assistant that wanted to help you browse the web better.  In 2000.', 'http://deepleap.com', 'http://dashes.com/anil/stuff/deepleapsticker.jpg');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'benbrown';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Inventor', '1998-11-01', '2000-08-01');
SELECT id INTO @maker_id FROM makers WHERE slug = 'adammathes';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Engineer', '2000-05-01', '2000-08-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('dropcash', 'Dropcash', 'Probably the first crowdfunding site on the web.', 'http://dropcash.com/', 'http://assets.killerstartups.com/reviews/logos/8b1b10575674f66a387d377e97f4973a.jpg');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'torrez';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator', '2003-11-01', '2007-06-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('dropload', 'Dropload', 'A site to share files via HTTP.', 'http://dropload.com/', 'http://www.eurosis.org/cms/files/drop-parachute-large.jpg');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'torrez';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator', '2002-05-01', '2007-05-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('fat-lab', 'Free Art & Technology Lab (FAT Lab)', 'An an organization dedicated to enriching the public domain through the research and development of creative technologies and media.', 'http://fffff.at', 'http://fffff.at/fuckflickr/data/MISC/thumb/FAT-logo-redeux.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'gleuch';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Virtual Research Fellow', '2009-05-01', NULL);
SELECT id INTO @maker_id FROM makers WHERE slug = 'jamiew';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Virtual Research Fellow', '2007-09-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('filepile', 'FilePile', 'A place for', 'http://filepile.org/', '');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'torrez';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator', '2000-06-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('fuelly', 'Fuelly', 'A social fuel economy tracker service', 'http://www.fuelly.com/', 'http://d217i264rvtnq0.cloudfront.net/images/fuelly/v2/logo.gif');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'matt-haughey';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Co-founder', '2008-08-01', NULL);
SELECT id INTO @maker_id FROM makers WHERE slug = 'paul-bausch';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Co-founder', '2008-08-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('grampa', 'Grampa', 'Cover the world in ad banners.', 'http://grampa.co', '');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'torrez';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator', '2012-04-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('harpers', 'Harpers.org', 'The website (archive, searchable index, consumer code) for Harper''s Magazine.', 'http://harpers.org', 'http://harpers.org/media/image/Harpers_305x100.gif');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'ftrain';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Responsible for the entire web presence, including an amazing Index.', '2005-02-01', '2010-04-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('healthmonth', 'Health Month', 'Try to stick to some healthy rules for a month, for fun.', 'http://healthmonth.com', 'http://healthmonth.com/images/tag-wheel.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'busterbenson';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Founder', '2010-05-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('knowyourmeme', 'Know Your Meme', 'Internet culture database', 'http://knowyourmeme.com', 'http://upload.wikimedia.org/wikipedia/en/thumb/c/c7/Knowyourmeme.jpg/180px-Knowyourmeme.jpg');
SELECT LAST_INSERT_ID() INTO @product_id;


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('landmarker', 'Landmarker', 'Original map-based site for bookmarking places in Second Life.', 'http://landmarker.neologasm.org/', '');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'markpasc';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator & programmer', '2005-05-01', '2006-05-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('lastyears', 'Last Year''s Model', 'A simple community site to encourage thoughtful consumption of gadgets.', 'http://lastyearsmodel.org/', 'http://dashes.com/anil/images/lastyears-badge.jpg');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'anildash';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'I created the site and promoted the hashtag, which still gets used regularly a few years later', '2009-04-01', NULL);
SELECT id INTO @maker_id FROM makers WHERE slug = 'mike-monteiro';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Designer', '2009-04-01', '2009-06-01');
SELECT id INTO @maker_id FROM makers WHERE slug = 'ginatrapani';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Contributed a testimonial to the site', '2009-04-01', '2009-04-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('laterspam', 'Laterspam', 'Block, report to Twitter, and get a point for every spam mention you report.', 'http://laterspam.org/', '');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'torrez';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator', '2011-09-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('leapforg', 'leapf.org', 'Read your internet neighborhood', 'http://leapf.org/', 'http://leapf.org/static/leapfrog/img/leapfrog-vimeo.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'markpasc';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Programmer', '2010-04-01', NULL);
SELECT id INTO @maker_id FROM makers WHERE slug = 'apparentlymart';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Occasional Programmer', '2010-04-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('lifehacker', 'Lifehacker', 'Productivity and software blog.', 'http://lifehacker.com', 'https://twimg0-a.akamaihd.net/profile_images/1861146796/Twitter_-_Avatar.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'ginatrapani';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Founding Editor', '2005-01-01', '2009-01-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('livejournal', 'LiveJournal', 'Blogging before it was called that', 'http://www.livejournal.com/', 'http://www.livejournalinc.com/images/logo.gif');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'apparentlymart';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Programmer', '1999-01-01', '2007-01-01');
SELECT id INTO @maker_id FROM makers WHERE slug = 'bradfitz';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator, Proprietor, Programmer', '1997-01-01', '2005-02-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('locavore', 'Locavore', 'iPhone app to find out what fruits and vegetables are in season near you.', 'http://www.getlocavore.com/', 'http://a1.mzstatic.com/us/r1000/099/Purple/d0/71/b9/mzl.vhtxysuz.175x175-75.jpg');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'busterbenson';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator', '2009-08-01', '2011-01-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('makeaface', 'Make A Face', 'A simple way to share your face with friends', 'http://make-a-face.org/', 'http://make-a-face.org/static-6/makeaface/banner.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'markpasc';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Programmer', '2010-01-01', '2010-08-01');
SELECT id INTO @maker_id FROM makers WHERE slug = 'apparentlymart';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Cheerleader, Occasional Programmer', '2010-01-01', '2010-08-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('makepixelart', 'Make pixel art', 'An HTML5/Javascript application that makes it easier to make pixel art.', 'http://makepixelart.com', 'http://makepixelart.com/img/icon-128x128.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'benbrown';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Designer', '2011-03-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('makerbase', 'Makerbase', 'IMDb for manipulators of bits and atoms', 'http://makerbase.neologasm.org/', '');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'markpasc';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Programmer', '2012-02-01', NULL);
SELECT id INTO @maker_id FROM makers WHERE slug = 'anildash';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Came up with the idea', '2011-11-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('metafetch', 'Metafetch', 'Collect and share content discovered across the web. Ideal for researching and organizing web content for research, design, art.', 'http://www.metafetch.com', 'http://metafetch-assets.s3.amazonaws.com/metafetch_150.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'gleuch';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Founder & Creator', '2011-05-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('metafilter', 'MetaFilter', 'A community weblog, and some other stuff', 'http://www.metafilter.com/', 'https://twimg0-a.akamaihd.net/profile_images/2163267027/apple-touch-icon.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'matt-haughey';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Founder', '1999-07-01', NULL);
SELECT id INTO @maker_id FROM makers WHERE slug = 'paul-bausch';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'CTO', '2006-05-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('mixel', 'Mixel', 'Creativity app for iPad', 'http://mixel.cc', 'http://writerbay.files.wordpress.com/2011/11/mixel-logo-large.png?w=288');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'khoi-vinh';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'made part of ', '2011-02-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('mlkshk', 'MLKSHK', 'A site for sharing pictures.', 'http://mlkshk.com/', 'https://mlkshk.com/r/2NOE');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'torrez';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator and programmer', '2011-01-01', NULL);
SELECT id INTO @maker_id FROM makers WHERE slug = 'amber';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator and designer', '2010-12-01', NULL);
SELECT id INTO @maker_id FROM makers WHERE slug = 'markpasc';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Contract API programmer & test writer', '2011-05-01', '2011-06-01');
SELECT id INTO @maker_id FROM makers WHERE slug = 'anildash';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'I gave a little bit of advice, some of it was even solicited.', '2010-12-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('movabletype', 'Movable Type', 'Your all-in-one social publishing platform', 'http://www.movabletype.com/', 'http://static.skattertech.com/files/2009/09/movable-type-logo.jpg');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'markpasc';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Occasional programmer', '2004-05-01', '2008-12-01');
SELECT id INTO @maker_id FROM makers WHERE slug = 'anildash';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'I managed the business division that made MT for a few years, and did lots of stuff with the community.', '2003-04-01', '2009-10-01');
SELECT id INTO @maker_id FROM makers WHERE slug = 'djacobs';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'I helped program widely-used plugins for MT, founded a company to help support  plugins and the core product, and managed the MT business.', '2004-04-01', '2010-10-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('mt-action-streams', 'Action Streams (Movable Type)', 'Collect accounts & activity from across the web into MT', 'http://plugins.movabletype.org/action-streams/', '');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'markpasc';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Programmer', '2008-02-01', '2011-10-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('narrowthegapp', 'Narrow the Gapp', 'A single-serving, data-driven web site about the gender wage gap in the U.S.', 'http://narrowthegapp.com', 'http://narrowthegapp.com/images/narrow-the-gapp.jpg');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'ginatrapani';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator', '2012-03-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('nutshell', 'Nutshell', 'A unified search box in IE.', 'http://kottke.org/02/02/nutshell-from-mr-torrez-is', '');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'torrez';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator', '2002-02-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('nxtclass', 'NXTClass', 'NXTClass is a free, open-source solution that makes it possible to create academic web sites in a matter of seconds.', 'http://nxtclass.tk', '');
SELECT LAST_INSERT_ID() INTO @product_id;


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('pixelpix', 'Pixel Pix', 'An iPhone app that turns photos into pixel art', 'http://makepixelart.com/pixelpixapp/', '');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'benbrown';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator', '2012-02-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('playfic', 'Playfic', 'Write and play interactive, text-based games from your browser.', 'http://playfic.com/', 'https://twimg0-a.akamaihd.net/profile_images/1829811712/cursor_shiny_icon.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'waxpancake';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator', '2012-02-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('stackexchange', 'Stack Exchange', 'Stack Exchange is a network of 85 question & answer sites on diverse topics.', 'http://stackexchange.com/', 'http://cdn.sstatic.net/stackexchange/img/logos/se/se-icon.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'anildash';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Helped brainstorm the original "Stack Overflow" name; Now I''m on the board.', '2008-07-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('tagsinput', 'jquery tags input', 'Magically convert a simple text input into a cool tag list.', 'http://xoxco.com/projects/code/tagsinput/', 'http://xoxco.com/new/img/logo.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'benbrown';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Creator', '2011-04-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('thinkup', 'ThinkUp', 'ThinkUp tracks your activity on social networks like Facebook, Twitter & Google+. It makes the web better.', 'http://thinkupapp.com/', 'https://lh4.googleusercontent.com/-v3-NmrLLUJY/T3O0leYLHFI/AAAAAAAAAFA/MbBMr7VIfqA/s250-c/thinkup-logo.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'anildash';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Co-founder, working on everything from running the business to hacking CSS.', '2009-06-01', NULL);
SELECT id INTO @maker_id FROM makers WHERE slug = 'ginatrapani';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Co-founder', '2009-06-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('todo.txt-apps', 'Todo.txt', 'Tools and file format for managing a text-based task list', 'http://todotxt.com', 'http://todotxt.com/images/todotxt_logo_2011.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'ginatrapani';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Founder and lead developer', '2006-05-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('twig', 'This Week in Google', 'A weekly web show about Google and the cloud.', 'http://twit.tv/twig', 'http://ginatrapani.org/imgs/twig-cover.jpg');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'ginatrapani';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Host', '2009-08-01', NULL);


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('twitter', 'Twitter', 'A microcontent social app', 'http://www.twitter.com/', 'http://netrightdaily.com/wp-content/uploads/2012/04/Twitter.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'evan-williams';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Co-founder', '2006-07-01', '2011-03-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('typepad', 'TypePad', 'Easily design and customize your own blog', 'http://www.typepad.com/', 'http://static.typepad.com/.shared:v20120327.01-0-ge7f167b:typepad:en_us/images/app/favicons/atip-129si.png');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'markpasc';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Occasional programmer', '2004-05-01', '2010-11-01');
SELECT id INTO @maker_id FROM makers WHERE slug = 'anildash';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Helped launch the service, working on everything from marketing to pricing to helping major users.', '2003-05-01', '2009-10-01');
SELECT id INTO @maker_id FROM makers WHERE slug = 'apparentlymart';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Programmer, API Designer', '2008-10-01', '2010-11-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('ubernu', 'Uber.nu', 'A daily satirical site that ran, daily, from 2000-2005.', 'http://uber.nu', 'http://uber.nu/img/nuber.gif');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'benbrown';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Founder', '2000-05-01', '2005-12-01');
SELECT id INTO @maker_id FROM makers WHERE slug = 'adammathes';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Founder', '2000-05-01', '2005-12-01');


INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('upcoming', 'Upcoming', 'One of the web''s first and most innovative social events sites.', 'http://upcoming.org/', 'http://www.beerandblog.com/wp-content/uploads/upcoming-logo.jpg');
SELECT LAST_INSERT_ID() INTO @product_id;
SELECT id INTO @maker_id FROM makers WHERE slug = 'waxpancake';
INSERT INTO participations (product_id, maker_id, role, start, end) VALUES (@product_id, @maker_id, 'Founded, developed,  and managed the community. Also sold it to Yahoo.', '2003-09-01', '2007-11-01');


