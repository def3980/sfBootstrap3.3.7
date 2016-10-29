<?php
/*
 *  $Id: Builder.php 4593 2008-06-29 03:24:50Z jwage $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Base class for any code builders/generators for Doctrine
 *
 * @package     Doctrine
 * @subpackage  Builder
 * @link        www.doctrine-project.org
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @since       1.0
 * @version     $Revision: 4593 $
 * @author      Jonathan H. Wage <jwage@mac.com>
 */
class Doctrine_Builder
{
    /**
     * Special function for var_export()
     * The normal code which is returned is malformed and does not follow Doctrine standards
     * So we do some string replacing to clean it up
     *
     * @param string $var
     * @return void
     */
    public function varExport($var) {
        $export = var_export($var, true);
        $export = str_replace("\n", PHP_EOL.str_repeat(' ', 50), $export);
        $export = str_replace('  ', ' ', $export);
        $export = str_replace('array (', 'array(', $export);
        $export = str_replace('array( ', 'array(', $export);
        $export = str_replace(',)', ')', $export);
        $export = str_replace(', )', ')', $export);
        $export = str_replace('  ', ' ', $export);

        if (is_array($var)) {
            $export = str_replace('             ', str_repeat(' ', 12), $export);
            $export = explode("\n", $export);
            $copia = $export;
            array_shift($copia); array_pop($copia);
            $lon = 0;
            foreach ($var as $k => $v):
                $lon = $lon < strlen($k) ? strlen($k) : $lon;
            endforeach;
            foreach ($var as $k2 => $v2):
                if (strlen($k2) != $lon) {
                    $aux = $lon - strlen($k2);
                    foreach ($copia as $int_k => $int_v):
                        if (false !== strpos($int_v, $k2)) {
                            $copia[$int_k] = str_replace("'$k2' => ", "'$k2'".str_repeat(' ', $aux)." => ", $int_v);
                            break;
                        }
                    endforeach;
                } else {
                    foreach ($copia as $int_k => $int_v):
                        if (false !== strpos($int_v, $k2)) {
                            $copia[$int_k] = str_replace("'$k2' => ", "'$k2' => ", $int_v);
                            break;
                        }
                    endforeach;
                }
            endforeach;

            array_unshift($copia, reset($export));
            array_push($copia, trim(end($export)));

            $export = "";
            foreach ($copia as $exp_k2 => $exp_v2):
//                $export .= count($copia) - 1 === $exp_k2 ? str_replace(')', str_repeat(' ', 8).')', $exp_v2) : $exp_v2.PHP_EOL;
                $export .= count($copia) - 1 === $exp_k2 ? str_replace(')', str_repeat(' ', 8).')', $exp_v2) : $exp_v2;
            endforeach;
        }

        return $export;
    }

}