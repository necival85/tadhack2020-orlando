<?php

class ApiDazeXML {
    public $xml = '';
    public function __construct($xml='') {
        $this->xml = $xml;
    } // END MAGIC construct

    ////////////////////////////
    //// Formatting Methods ////
    ////////////////////////////

    public function a($add='', $prepend=false) {
        if (trim($add) == '') { return $this; }
        if ($prepend) {
            $this->xml = trim($add) . "\n" . trim($this->xml);
        } else {
            $this->xml = trim($this->xml) . "\n" . trim($add);
        } // END IF prepend
        return $this;
    } // END METHOD a

    public function doc() {
        if (stripos($this->xml, '<work>') === false) {
            $this->a('<work>', true);
        }
        if (stripos($this->xml, '<document>') === false) {
            $this->a('<document>', true);
        }
        if (stripos($this->xml, '</work>') === false) {
            $this->a('</work>');
        }
        if (stripos($this->xml, '</document>') === false) {
            $this->a('</document>');
        }
        return $this;
    } // END METHOD doc

    public function output() {
        $this->doc();
        xml_response($this->xml);
        return $this;
    } // END METHOD output



    //////////////////////////////
    //// Call Control Methods ////
    //////////////////////////////

    public function answer() {
        $this->a('<answer/>');
        return $this;
    } // END METHOD answer

    public function bind() {
        $this->a('<bind/>');
        return $this;
    } // END METHOD bind

    public function dial($number='') {
        if ($number == '') { return $this; }
        $this->a('<dial>' . $number . '</dial>');
        return $this;
    } // END METHOD dial

    public function echo($text='') {
        if ($text == '') { return $this; }
        $this->a('<echo>' . $text . '</echo>');
        return $this;
    } // END METHOD echo

    public function hangup() {
        $this->a('<hangup/>');
        return $this;
    } // END METHOD hangup

    public function intercept() {
        $this->a('<intercept/>');
        return $this;
    } // END METHOD intercept

    public function playback($url='') {
        if ($url == '') { return $this; }
        $this->a('<playback>' . $url . '</playback>');
        return $this;
    } // END METHOD playback

    public function ringback() {
        $this->a('<ringback/>');
        return $this;
    } // END METHOD ringback

    public function speak($text='') {
        if ($text == '') { return $this; }
        $this->a('<speak>' . $text . '</speak>');
        return $this;
    } // END METHOD speak

    public function wait($seconds=0) {
        if (!is_int($seconds)) { $seconds = (int) $seconds; }
        if ($seconds == 0) { return $this; }
        $this->a('<wait>' . $seconds . '</wait>');
        return $this;
    } // END METHOD wait


} // END CLASS ApiDazeXML

?>