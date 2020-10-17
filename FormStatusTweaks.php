<?php
/**
 * REDCap External Module: FormStatusTweaks
 * Specify alternative text to be used in place of the standard Incomplete/Unverified/Complete form status labels.
 * @author Luke Stevens, Murdoch Children's Research Institute
 */
namespace MCRI\FormStatusTweaks;

use ExternalModules\AbstractExternalModule;

class FormStatusTweaks extends AbstractExternalModule
{
        /**
         * redcap_every_page_before_render($project_id=null)
         * Catch a report view or export and if there's some "extended" config apply the necessary tweaks.
         */
        public function redcap_every_page_before_render($project_id=null) {
                global $Proj, $lang;
                $label0global = $lang['global_92']; // Incomplete
                $label1global = $lang['global_93']; // Unverified
                $label2global = $lang['survey_28']; // Complete
                $label0system = $this->getSystemSetting('system-label-0');
                $label1system = $this->getSystemSetting('system-label-1');
                $label2system = $this->getSystemSetting('system-label-2');
                $label0 = $label0system ?: $label0global;
                $label1 = $label1system ?: $label1global;
                $label2 = $label2system ?: $label2global;

                if (!is_null($project_id)) {
                        $label0project = $this->getProjectSetting('project-label-0');
                        $label1project = $this->getProjectSetting('project-label-1');
                        $label2project = $this->getProjectSetting('project-label-2');

                        $label0 = $label0project ?: $label0;
                        $label1 = $label1project ?: $label1;
                        $label2 = $label2project ?: $label2;

                        foreach ($Proj->metadata as $f) {
                                if ($f['element_enum']=='0, Incomplete \\n 1, Unverified \\n 2, Complete') {
                                        $Proj->metadata[$f['field_name']]['element_enum'] = "0, $label0 \\n 1, $label1 \\n 2, $label2";
                                }
                        }

                        $formSettings = $this->getSubSettings('project-label-instrument', $project_id);
                        foreach ($formSettings as $fs) {
                                $label0instrument = $fs['instrument-label-0'] ?: $label0;
                                $label1instrument = $fs['instrument-label-1'] ?: $label1;
                                $label2instrument = $fs['instrument-label-2'] ?: $label2;
                                $Proj->metadata[$fs['instrument']."_complete"]['element_enum'] = "0, $label0instrument \\n 1, $label1instrument \\n 2, $label2instrument";
                        }
                }

                $lang['global_92'] = $label0;
                $lang['global_93'] = $label1;
                $lang['survey_28'] = $label2;
        }
}