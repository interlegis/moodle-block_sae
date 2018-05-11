<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

class block_sae extends block_base {

    /**
     *
     * Download previously issued certificates.
     *
     * Displays all previously issued certificates for logged in user.
     */
    public function init() {
        $this->title   = get_string('download_certificates', 'block_download_certificates');
        $this->version = 2016081200;
    }

    /**
     *
     * Restricting Applicable formats.
     *
     * Restricting where the blocks can appear on the site (Frontpage and My Learning page only).
     */
    public function applicable_formats() {
        return array('all' => true,
                     'course-view' => false,
                     'mod' => false);
    }

    /**
     *
     * Multiple instances of the block.
     *
     * Allowing multiple instance of the block to appear throughtout the site pages.
     */
    public function instance_allow_multiple() {
          return true;
    }

    /**
     *
     * Retrieving relevant required data.
     *
     * Retrieving data and populating them for displaying on the block.
     */
    public function get_content() {
        global $USER, $DB, $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        // User ID.
        $userid = optional_param('userid', $USER->id, PARAM_INT);

        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        // Table headers.
        $table = new html_table();

        $sql = "SELECT f.id AS fid, f.userid AS fuserid, f.contextid AS fcontextid, f.filename AS ffilename,
                       ctx.id AS ctxid, ctx.contextlevel AS ctxcontextlevel, ctx.instanceid AS ctxinstanceid,
                       cm.id AS cmid, cm.course AS cmcourse, cm.module AS cmmodule, cm.instance AS cminstance,
                       crt.id AS crtid, crt.course AS crtcourse, crt.name AS crtname, ci.id AS ciid,
					   ci.userid AS ciuserid, ci.certificateid AS cicertificateid, ci.code AS cicode,
					   ci.timecreated AS citimecreated, c.id AS cid, c.fullname AS cfullname,
					   c.shortname AS cshortname
                  FROM {files} f
            INNER JOIN {context} ctx
                    ON ctx.id = f.contextid
            INNER JOIN {course_modules} cm
                    ON cm.id = ctx.instanceid
            INNER JOIN {certificate} crt
                    ON crt.id = cm.instance
             LEFT JOIN {certificate_issues} ci
                    ON ci.certificateid = crt.id
            INNER JOIN {course} c
                    ON c.id = crt.course

				 WHERE f.userid = ci.userid AND
				       f.userid = :userid AND
				    f.component = 'mod_certificate' AND
                     f.mimetype = 'application/pdf'
		      ORDER BY ci.timecreated DESC";
            // PDF FILES ONLY (f.mimetype = 'application/pdf').

        $limit = " LIMIT 5"; // Limiting the output results to just five records.
        $certificates = $DB->get_records_sql($sql.$limit, array('userid' => $USER->id));

        if (empty($certificates)) {
            // No Certificate Issued - Print error message.
            $this->content->text = get_string('download_certificates_noreports', 'block_download_certificates');
            return $this->content;
        } else {
            foreach ($certificates as $certdata) {

                $certdata->printdate = 1; // Modify printdate so that date is always printed.
                $certdata->printgrade = 1; // Modify printgrade so that grade is always printed.
                $certdata->gradefmt = 1;
                // Modify gradefmt so that correct suffix is printed. 1=percentage, 2=points and 3=letter.

                $certrecord = new stdClass();
                $certrecord->timecreated = $certdata->citimecreated;

                // Date format.
                $dateformat = get_string('strftimedate', 'langconfig');

                // Required variables for output.
                $userid = $certrecord->userid = $certdata->fuserid;
                $certificateissueid = $certrecord->certificateissueid = $certdata->ciid;
                $contextid = $certrecord->contextid = $certdata->ctxid;
                $courseid = $certrecord->id = $certdata->cid;
                $coursename = $certrecord->fullname = $certdata->cfullname;
                $filename = $certrecord->filename = $certdata->ffilename;
                $certificatename = $certrecord->name = $certdata->crtname;
                $code = $certrecord->code = $certdata->cicode;

                // Retrieving grade and date for each certificate.
                $grade = certificate_get_grade($certdata, $certrecord, $userid, $valueonly = true);
                $date = $certrecord->timecreated = $certdata->citimecreated;

                // Linkable Direct course. Use $courselink for clickable course link.
                $courselink = html_writer::link(new moodle_url('/course/view.php', array('id' => $courseid)),
                "<strong>" . $coursename . "</strong>", array('fullname' => $coursename))  . "<br>" .
                " Certificate: " . $certificatename . "<br>" .
                " [Issued on: " . userdate($date, $dateformat) . " | Code: " . $code . "]";

                // Non - Linkable course title only. The course link isn't linkable.
                $link = "<strong>" . $coursename . "</strong>" . "<br>" .
                "[" . $certificatename . " | " . userdate($date, $dateformat) . " | " . $code . "]";

                // Direct certificate download link.
                $filelink = file_encode_url($CFG->wwwroot.'/pluginfile.php', '/'
                .$contextid . '/mod_certificate/issue/' . $certificateissueid . '/' . $filename);

                $imglink = html_writer::empty_tag('img', array('src' => new moodle_url(
                '/blocks/download_certificates/pix/download.png'), 'alt' => "Please download", 'height' => 40, 'width' => 40));

                $outputlink = '<a href="'.$filelink.'" >' . $imglink . '</a>';
                $table->data[] = array ($link, $outputlink);

            }

                 $this->content->footer = html_writer::link(new moodle_url('/blocks/download_certificates/report.php',
                                 array('userid' => $USER->id)),
                                 get_string('download_certificates_footermessage', 'block_download_certificates'));

        }

        $this->content->text = html_writer::table($table);
        return $this->content;
    }
}
