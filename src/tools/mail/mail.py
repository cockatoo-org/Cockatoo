#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
report.py - mail report

Multipart message support.

 """
__author__ = "hiroaki.kubota <hiroaki.kubota@mail.rakuten.com>"
__date__ = "2012/02/27"
__credits__ = "Copyright (C) 2012, rakuten"
__version__ = "Version $Id$"


import sys , os , codecs , re , getopt , time 
import quopri
import smtplib
from email.MIMEText import MIMEText
if ( sys.version_info >= (2, 6, 0) ):
    from email.mime.application import MIMEApplication
else:
    class MIMEApplication:
        def __init__(self,data,type):
            raise 'The type of "octet-stream" is not supported in this Python'+str(sys.version_info)
        
        
from email.MIMEMultipart import MIMEMultipart
from email.Header import Header
from email.Utils import formatdate
from email import Charset
sys.stdout = codecs.getwriter('utf-8')(sys.stdout)
sys.stderr = codecs.getwriter('utf-8')(sys.stderr)

#-----------------------------
# Default valuse
#-----------------------------
encode = 'utf-8'
t = time.strftime('Report_%Y-%m-%d')
mail_to  = []
mail_to_str = ''
mail_from= ''
subject = t
body_file = None
ext = False
ext_files = []
extb = False
extb_files = []
#-----------------------------
# Message
#-----------------------------
def usage (code):
    """
    Print message and eixt
    """
    msg = """
Usage: report_mail.py  [options]
Watch text logs and do action, when the line appeared regex matching on settings
Options:
 -h,--help:
            This message.
 -t,--to  <e-mail>:
            to
 -f,--from  <e-mail>:
            from
 -s,--subject  <subject>:
            subject
 -b,--body  <path>:
            body file.
 -e,--ext  <path>:
            extention text file.
 -E,--ext-binary <path>:
            extention binary file.
 -c,--charset  <charset>:
            charset.
"""
    print >>sys.stdout,msg
    sys.exit(code)

#-----------------------------
# Option parse
try:
    optlist, args = getopt.getopt(sys.argv[1:],"ht:f:s:b:e:E:c:",longopts=["help","to=","from=","subject=","body=","ext=","ext-binary=","charset="])
    for opt, args in optlist:
        print opt
        if opt in ('-h','--help'):
            debug_out(DEBUG_MSG,'help')
            usage(0)
        if opt in ('-t','--to'):
            mail_to.append(args)
        if opt in ('-f','--from'):
            mail_from = args
        if opt in ('-s','--subject'):
            subject = args
        if opt in ('-b','--body'):
            body_file = args
        if opt in ('-e','--ext'):
            ext = True
            ext_files.append(args)
        if opt in ('-E','--ext-binary'):
            extb = True
            extb_files.append(args)
        if opt in ('-c','--charset'):
            encode = args

except SystemExit:
    raise
except:
    usage(1)

print 'encode       : ' + encode
for to in mail_to:
    mail_to_str += ';' + to
print 'mail_to      : ' + mail_to_str
print 'mail_from    : ' + mail_from
print 'subject      : ' + subject
print 'body_file    : ' + body_file
if ext:
    for ext_file in ext_files:
        print 'ext_file     : ' + ext_file
if extb:
    for extb_file in extb_files:
        print 'extb_file    : ' + extb_file

#----------------------------------------------
mail = None

if body_file == '-':
    body_file='/dev/stdin'
    
fp_body = open(body_file,'rb')
body = unicode(fp_body.read(),'utf-8')
fp_body.close()

Charset.add_charset('utf-8', Charset.QP, Charset.QP, 'utf-8')
body_mime = MIMEText(body.encode(encode),'plain',encode)

def set_header(msg):
    msg['Subject'] = Header(subject.encode(encode),encode)
    msg['To'] = mail_to_str
    msg['From'] = mail_from
    msg['Date'] = formatdate()

if ext or extb :
    mail = MIMEMultipart()
    set_header(mail)
    for ext_file in ext_files:
        fp_ext = open(ext_file,'rb')
        ext = fp_ext.read()
        fp_ext.close()
        related = MIMEMultipart('related')
        related.attach(body_mime)
        ext_filename = os.path.basename(ext_file)
        related.attach(MIMEText(ext,'text/application/octet-stream; name='+ext_filename,encode))
        mail.attach(related)
    for extb_file in extb_files:
        fp_extb = open(extb_file,'rb')
        extb = fp_extb.read()
        fp_extb.close()
        related = MIMEMultipart('related')
        related.attach(body_mime)
        extb_filename = os.path.basename(extb_file)
        related.attach(MIMEApplication(extb,'octet-stream; name='+extb_filename))
        mail.attach(related)
else :
    mail = body_mime
    set_header(mail)
#----------------------------------------------
s = smtplib.SMTP()
s.connect()
s.sendmail(mail_from,mail_to,mail.as_string())
s.close()
