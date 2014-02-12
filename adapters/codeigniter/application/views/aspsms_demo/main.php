<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>ASPSMS PHP Library Adapter for CodeIgniter - DEMO</title>
        <style>
            label {
                display: block;
            }
            input[type=text], textarea {
                width: 500px;
            }
            textarea {
                min-height: 50px;
            }
        </style>
    </head>
    <body>
        <h1>ASPSMS PHP Library Adapter for CodeIgniter - DEMO</h1>
        
        <h2>Table of Contents</h2>
        
        <ol>
            <li>Installation</li>
            <li>Demos
                <ol>
                    <li>Status / General
                        <ol>
                            <li>Check credit balance</li>
                        </ol>
                    </li>
                    <li>Messaging
                        <ol>
                            <li>Send text</li>
                            <li>Send two trackable texts</li>
                            <li>Track two</li>
                        </ol>
                    </li>
                    <li>Originator
                        <ol>
                            <li>Check validity of originator</li>
                            <li>Request originator unlock code</li>
                            <li>Unlock originator</li>
                        </ol></li>
                    <li>Tokens
                        <ol>
                            <li>Send token</li>
                            <li>Validate token</li>
                        </ol>
                    </li>
                    <li>Special and/or binary messages (XML-Interface)
                        <ol>
                            <li>Send WAP (also SOAP/HTTP)</li>
                            <li>Send VCard</li>
                        </ol>
                    </li>
                </ol>
            </li>
        </ol>
        
        <h2>1. Installation</h2>
        <ol>
            <li>Place adapter files according to their location (application/language is optional)</li>
            <li>Place <em>/lib/Aspsms</em> folder as is in <em>codeigniter/application/third_party</em></li>
            <li>Configure <em>application/config/aspsms.php</em> with your aspsms.com credentials.</li>
        </ol>

        <p>In controller load `aspsms` library as you usually could and call analogous as in demo.php
(but through CI library calling convention, naturally).</p>
        </ol>
        <hr/>
        
        <h2>2. Demos</h2>
        
        <h3>2.1. General</h3>
        
        <h4>2.1.1. Check credit balance</h4>
        <?php echo form_open('aspsms_demo/check_balance'); ?>
        <button>Check balance</button>
        <?php echo form_close(); ?>
        
        
        <h3>2.2. Messaging</h3>
        
        <h4>2.2.1. Send sext</h4>
        <?php echo form_open('aspsms_demo/send_text'); ?>
        <label>Recipient(s)</label>
        <input name="recipient" placeholder="00414477777XX[;004144XXXXX[;004144YYYYYY[;...]]]" type="text"/>
        <label>Override originator (optional)</label>
        <input name="originator" placeholder="Your Name" type="text"/>
        <label>Text</label>
        <textarea name="text">Hello pretty. äëïöü áéíóú âêîû çñ</textarea>
        <br/>
        <button>Send Text</button>
        <?php echo form_close(); ?>
        
        
        <h4>2.2.2. Send two trackable texts</h4>
        <?php echo form_open('aspsms_demo/send_two_trackables'); ?>
        <table>
            <thead>
                <tr>
                    <th>Tracking-Nr.</th>
                    <th>Recipient</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input name="trackingnr[]" value="1001" type="text"/>
                    </td>
                    <td>
                        <input name="recipient[]" placeholder="00414477777XX" type="text"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input name="trackingnr[]" value="1002" type="text"/>
                    </td>
                    <td>
                        <input name="recipient[]" placeholder="00414477777XX" type="text"/>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <label>Text</label>
        <textarea name="text">Hello pretty. äëïöü áéíóú âêîû çñ</textarea>
        <br/>
        <button>Send Text</button>
        <?php echo form_close(); ?>
        
        <h4>2.2.3. Track two</h4>
        <?php echo form_open('aspsms_demo/track','method="GET"'); ?>
        <label>Tracking-Nr.</label>
        <input name="trackingnr" value="1001;1002" type="text"/>
        <br/>
        <button>Request delivery information</button>
        <?php echo form_close(); ?>
        
        
        <hr/>
        
        
        <h3>2.3. Originator validation</h3>
        <p>
            For security reasons numeric originators can only be used once they
            have been verified to belong to the ASPSMS user.
        </p>
        
        <h4>2.3.1. Check validity of originator</h4>
        <label>Originator</label>
        <?php echo form_open('aspsms_demo/check_originator'); ?>
        <input name="originator" placeholder="0031677777XX" type="text"/>
        <br/>
        <button>Check</button>
        <?php echo form_close(); ?>
        
        <h4>2.3.2. Request originator unlock code</h4>
        <label>Originator</label>
        <?php echo form_open('aspsms_demo/request_originator_unlock'); ?>
        <input name="originator" placeholder="0031677777XX" type="text"/>
        <br/>
        <button>Check</button>
        <?php echo form_close(); ?>
        
        <h4>2.3.3. Unlock originator</h4>
        <?php echo form_open('aspsms_demo/unlock_originator'); ?>
        <label>Code</label>
        <input name="code" placeholder="&lt;Code you get from above request&gt;" type="text"/>
        <label>Originator</label>
        <input name="originator" placeholder="0031677777XX" type="text"/>
        <br/>
        <button>Check</button>
        <?php echo form_close(); ?>
        
        <hr/>
        
        
        <h3>2.4. Token</h3>
        <p>
            Tokens serves two purposes:
        </p>
        <ol>
            <li>Verify ownership of a SMS receiver</li>
            <li>Additional authentication step through use of an alternative communication means once ownership has been verified.</li>
        </ol>
        
        <h4>2.4.1. Send token</h4>
        <?php echo form_open('aspsms_demo/send_token'); ?>
        <label>Recipient / PhoneNumber</label>
        <input name="recipient" placeholder="003167777XX" type="text"/>
        <label>Reference</label>
        <input name="reference" value="1234000" type="text"/>
        <label>Token Mask*</label>
        <input name="mask" value="##AANN" type="text" type="text"/>
        <dl>
            <dt>#</dt>
            <dd>Will be replaced with digit</dd>
            <dt>A</dt>
            <dd>Will be replaced with alphabetic character</dd>
            <dt>N</dt>
            <dd>Will be replaced with alphanumeric character</dd>
            <dt>Other</dt>
            <dd>Will be used as is</dd>
        </dl>
        <label>Message</label>
        <textarea name="message">Hello pretty. Here &lt;VERIFICATIONCODE&gt; is your code.</textarea>
        <label>Lifetime [min]</label>
        <input name="minutes" value="5" type="text" type="text"/>
        <label>Is token case sensitive?</label>
        <input name="case_sensitive" value="1" type="checkbox" checked="checked"/>
        <p>
            Note: the author could not successfully test this feature, so please make sure that it works yourself.
        </p>
        <br/>
        <button>Send token</button>
        <?php echo form_close(); ?>
        
        <h4>2.4.2. Validate token</h4>
        
        <?php echo form_open('aspsms_demo/validate_token'); ?>
        <label>Recipient / PhoneNumber</label>
        <input name="recipient" placeholder="003167777XX"/>
        <label>Reference</label>
        <input name="reference" value="1234000"/>
        <label>Token / Verification Code</label>
        <input name="token"/>
        <br/>
        <button>Validate token</button>
        <?php echo form_close(); ?>
        
        <hr/>
        
        <h3>2.5. Special and/or binary messages (XML-interface)</h3>
        
        <p>
            NOTE: For binary or special messages to successfully be delivered the
            recipient mobiles require the necessary capabilities. Some message types
            are now obsolete and have been succeeded by MMS or other data formats.
        </p>
        
        <p>
            2nd NOTE: This section is incomplete as the binary requests are still
            pending in development (due to unclarities and/or missing documentation).
        </p>
        
        <h4>2.5.1. Send WAP (also SOAP, HTTP)</h4>
        <?php echo form_open('aspsms_demo/send_wap'); ?>
        <label>Recipient(s)</label>
        <input name="recipient" placeholder="00414477777XX" type="text"/>
        <label>URL</label>
        <input name="url" placeholder="URL to your resource" type="text"/>
        <label>Description</label>
        <textarea name="description">It's not a virus!</textarea>
        <br/>
        <button>Send Text</button>
        <?php echo form_close(); ?>
        
        <h4>2.5.2. Send VCard</h4>
        <?php echo form_open('aspsms_demo/send_vcard'); ?>
        <label>Recipient(s)</label>
        <input name="recipient" placeholder="00414477777XX" type="text"/>
        <label>VCard Name</label>
        <input name="vcard_name" placeholder="Taxis Zürich" type="text"/>
        <label>VCard Phonenumber</label>
        <input name="vcard_phone" placeholder="0041447777777" type="text"/>
        <br/>
        <button>Send Text</button>
        <?php echo form_close(); ?>
        
    </body>
</html>
